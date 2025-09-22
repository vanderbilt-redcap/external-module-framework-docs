<?php

namespace Vanderbilt\ModuleDevelopmentExamples;

class ModuleDevelopmentExamples extends \ExternalModules\AbstractExternalModule
{
	/**
	 * @return void
	 */
	public function setupExampleActions() {
		$this->initializeJavascriptModuleObject();
		?>
        <button id='ajax-request'>AJAX Request Example</button>
        <button id='add-log-entry'>Add a log entry</button><br>
        <br>
        <script>
            (function(){
                const module = <?=$this->getJavascriptModuleObjectName()?>;

                const createRandomString = () => {
                    return Math.random().toString()
                }

                const handleRequest = (promise, randomNumber, next) => {
                    promise.then(response => {
                        // Make sure the random number given is returned.
                        if(response === randomNumber){
                            if(next === undefined){
                                alert('The test completed successfully.')
                            }
                            else{
                                next()
                            }
                        }
                        else{
                            alert("Received " + response + " instead of the expected " + randomNumber)
                        }
                    }).catch(err => {
                        alert('The request failed with an error: ' + err)
                    })
                }

                document.querySelector('button#add-log-entry').onclick = <?=$this->getTestAjaxJavascript()?>

                document.querySelector('button#ajax-request').onclick = () =>{
                    const randomNumber = createRandomString()

                    handleRequest(
                        module.ajax('example-action', randomNumber),
                        randomNumber
                    )                      
                }

                document.querySelectorAll('button.ajax').forEach((button) => {
                    button.addEventListener('click', (e) => {
                        const data = new URLSearchParams()
                
                        let url
                        if(button.dataset.includeCsrfToken !== undefined){
                            data.append('redcap_csrf_token', <?=json_encode($this->getCSRFToken())?>)
                            
                            if(button.dataset.apiUrl !== undefined){
                                url = <?=json_encode($this->getUrl('ajax-test.php', false, true))?>;
                            }
                            else{
                                url = <?=json_encode($this->getUrl('ajax-test.php'))?>;
                            }
                
                            if(button.dataset.noauth !== undefined){
                                url += '&NOAUTH'
                            }
                        }
                        else{
                            url = <?=json_encode($this->getUrl('ajax-test-no-csrf.php'))?>;
                        }
                
                        fetch(url, {
                            method: 'POST',
                            credentials: 'same-origin',
                            body: data
                        })
                        .then(response => response.text())
                        .then(data => {
                            if(data === 'success'){
                                alert('POST was successful!')
                            }
                            else{
                                alert('The POST failed with the following response: ' + data)
                            }
                        })
                    })
                })

                <?php if (isset($_GET['NOAUTH'])) { ?>
                    const ajaxAfterLegacyGetButton = document.createElement('button')
                    ajaxAfterLegacyGetButton.innerHTML = 'Test module.ajax() After Legacy AJAX GET'
                    ajaxAfterLegacyGetButton.onclick = () => {
                        const makeFirstRequest = () => {
                            const randomNumber = createRandomString()
                            handleRequest(
                                fetch(<?=json_encode($this->getUrl('example-action.php', true))?> + '&randomNumber=' + randomNumber, {
                                    method: 'GET',
                                    credentials: 'same-origin',
                                }).then(response => response.text()),
                                randomNumber,
                                makeSecondRequest
                            )
                        }

                        const makeSecondRequest = () => {
                            const randomNumber = createRandomString()
                            handleRequest(
                                module.ajax('example-action', randomNumber),
                                randomNumber
                            )
                        }

                        makeFirstRequest()
                    }

                    document.currentScript.insertAdjacentElement('beforebegin', ajaxAfterLegacyGetButton)
                <?php } ?>
            })()
        </script>
        <?php
	}

	public function getTestAjaxJavascript() {
		return "
            () => {
                module
                    .log('test log from Module Development Examples module')
                    .then(logId => {
                        alert('A log with ID ' + logId + ' was successfully added!')
                    }).catch(err => {
                        console.error(err)
                        alert('An error occurred while adding the log entry!  See the browser console for details.')
                    })
            }
        ";
	}

	/**
	 * @psalm-suppress PossiblyUnusedParam
	 */
	public function redcap_module_ajax($action, $payload, $project_id, $record, $instrument, $event_id, $repeat_instance, $survey_hash, $response_id, $survey_queue_hash, $page, $page_full, $user_id, $group_id) {
		if ($action === 'example-action') {
			return $payload;
		} else {
			return 'Unknown ajax action!';
		}
	}

	public function redcap_survey_page() {
		$this->initializeJavascriptModuleObject();
		?>
        <script>
            (() => {
                const module = <?=$this->getJavascriptModuleObjectName()?>;
                module.testAjax = <?=$this->getTestAjaxJavascript()?>;

                console.log('Run "<?=$this->getJavascriptModuleObjectName()?>.testAjax()" to test module ajax requests from this survey.')
            })()
        </script>
        <?php
	}

	public function redcap_module_api_before($project_id, $post) {
		// This example also functions as a pseudo unit in REDCap core test for this hook.
		if (((int)$project_id != $project_id)) {
			return 'API request failed likely due to invalid project ID detection in REDCap core.';
		} elseif ($post['some_key_that_disallows_this_request']) {
			return 'This API request is not allowed.';
		}
	}

	/**
	 * @psalm-suppress PossiblyUnusedParam
	 */
	public function redcap_module_api($action, $payload, $project_id, $user_id, $format, $returnFormat, $csvDelim) {
		if ($returnFormat != "json") {
			return $this->framework->apiErrorResponse("This API only supports JSON as return format!", 400);
		}
		switch ($action) {
			case "get-item": return $this->get_item($payload);
			case "list-items": return $this->list_items();
			case "add-item": return $this->add_item($payload);
			case "remove-item": return $this->remove_item($payload);
		}
	}

	public function example_cron() {
		$this->setSystemSetting('system-text', 'The example cron last ran on ' . date('c'));
	}

	/**
	 * An example of loading a custom twig extension (a filter, in this case).
	 */
	public function loadTwigExtensions() {
		$filter = new \Twig\TwigFilter('emoticon', function ($string) {
			return $string . ' :‑)';
		});
		$this->getTwig()->addFilter($filter);
	}

	#region API Methods

	// Note: The items store shared across all projects/users (incl. no-auth) and
	// implemented via the Framework's logging system (and overriding the project id)
	public const ITEM_STORE = "MyItemStore";

	public function add_item($payload) {
		$name = "". ($payload["item-name"] ?? "");
		if ($name == "") {
			return $this->framework->apiErrorResponse("Must specify 'item-name'!", 400);
		}
		$id = \Crypto::getGuid();
		$this->framework->log(self::ITEM_STORE, [
			"project_id" => null,
			"id" => $id,
			"name" => $name
		]);
		return $this->framework->apiJsonResponse([
			"item-id" => $id
		]);
	}

	public function get_item($payload) {
		$id = "". ($payload["item-id"] ?? "");
		if ($id == "") {
			return $this->framework->apiErrorResponse("Must specify 'item-id'!", 400);
		}
		$result = $this->framework->queryLogs("SELECT name WHERE message = ? AND id = ? AND ISNULL(project_id)", [self::ITEM_STORE, $id]);
		while ($row = $result->fetch_assoc()) {
			return $this->framework->apiJsonResponse([
				"item-id" => $id,
				"item-name" => $row["name"]
			]);
		}
		return $this->framework->apiErrorResponse("Could not find item with id '$id'.", 404);
	}

	public function list_items() {
		$list = [];
		$result = $this->framework->queryLogs("SELECT id, name WHERE message = ? AND ISNULL(project_id)", [self::ITEM_STORE]);
		while ($row = $result->fetch_assoc()) {
			$list[] = [
				"item-id" => $row["id"],
				"item-name" => $row["name"]
			];
		}
		return $this->framework->apiJsonResponse($list);
	}

	public function remove_item($payload) {
		$id = "". ($payload["item-id"] ?? "");
		if ($id == "") {
			return $this->framework->apiErrorResponse("Must specify 'item-id'!", 400);
		}
		$result = $this->framework->queryLogs("SELECT 1 WHERE message = ? AND id = ? AND ISNULL(project_id)", [self::ITEM_STORE, $id]);
		if ($result->num_rows !== 1) {
			return $this->framework->apiErrorResponse("No item with id '$id'.", 404);
		} else {
			$this->framework->removeLogs("message = ? AND id = ? AND ISNULL(project_id)", [
				self::ITEM_STORE, $id
			]);
		}
		return $this->framework->apiResponse(); // Could be null or void
	}

	#endregion

}
