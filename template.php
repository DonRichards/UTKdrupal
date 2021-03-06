<?php
/**
 * @file
 * Template.php - functions to manipulate Drupal's default markup.
 *
 * Drupal Print Message drupal_set_message()
 *   dpm($input, $name = NULL);
 * Drupal Variable Dump
 *   dvm($input, $name = NULL);
 *   dsm($form_id);
 * Drupal Pretty-Print prints to browser
 *   dpr($input, $return = FALSE, $name = NULL);
 * Drupal arguments
 *   dargs();
 * dd();
 * ddebug_backtrace();
 * db_queryd($query, $args = array());
 *
 * Additional Examples of functions
 * function UTKdrupal_preprocess_region(&$variables) {
 *   // Region preprocessing, switch on region name in var's, $variables['region']  <-name
 * }
 * function UTKdrupal_js_alter(&$javascript) {
 * }
 * function UTKdrupal_css_alter(&$css) {
 * }

 *
 * Modify any theme hooks variables or add your own variables, using preprocess or process functions.
 * Override any theme function. That is, replace a module's default theme function with one you write.
 * Call hook_*_alter() functions which allow you to alter various parts of Drupal's internals, including
 * the render elements in forms. The most useful of which include hook_form_alter(),
 * hook_form_FORM_ID_alter(), and hook_page_alter(). See api.drupal.org for more information about
 * _alter functions. Or link to this file's description https://www.drupal.org/node/1728096
*/

/**
 * Pragma (HTTP/1.0) and cache-control (HTTP/1.1) Prevent the client from caching the response.
 * @method UTKdrupal_page_headers
 */
function UTKdrupal_page_headers(){
  drupal_set_html_head('<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">');
  drupal_set_html_head('<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">');
}

/**
 * Set Logo path and $head variable in page.tpl.php is updated from what it was originally set to in template_preprocess_page().
 * @method UTKdrupal_preprocess_page
 * @param  [type]                    $variables Page variables
 * @param  [type]                    $hook      URL Hooks
 */
function UTKdrupal_preprocess_page(&$variables, $hook) {
  global $base_path;
  $variables['logopath'] = $base_path.'/' . drupal_get_path('theme','UTKdrupal') . '/logo.png';
  if(drupal_is_front_page()) {
    drupal_set_html_head('');
    $variables['head'] = drupal_get_html_head();
  }
	$header = drupal_get_http_header("status");
  if($header == "404 Not Found") {
    $variables['theme_hook_suggestions'][] = 'page__404';
  }
  if($header == "403 Forbidden") {
    $variables['theme_hook_suggestions'][] = 'page__404';
  }
}


/**
 * Change the text on the label element, Toggle label visibilty, define size of
 * the textfield, Set a default value for the textfield, Add extra attributes to
 * the text box, Prevent user from searching the default text, Alternative
 * (HTML5) placeholder attribute instead of using the javascript
 * @method UTKdrupal_form_search_block_form_alter
 * @param  [type]                                 $form       Form Name
 * @param  [type]                                 $form_state Form State Name
 * @param  [type]                                 $form_id    Node ID
*/
 /**
 * hook_form_FORM_ID_alter
 */
function UTKdrupal_form_search_block_form_alter(&$form, &$form_state, $form_id) {
    $form['search_block_form']['#title'] = t('Search');
    $form['search_block_form']['#title_display'] = 'invisible';
    $form['search_block_form']['#size'] = 20;
    $form['search_block_form']['#default_value'] = t('Search');
    $form['search_block_form']['#attributes']['onblur'] = "if (this.value == '') {this.value = 'Search';}";
    $form['search_block_form']['#attributes']['onfocus'] = "if (this.value == 'Search') {this.value = '';}";
    $form['#attributes']['onsubmit'] = "if(this.search_block_form.value=='Search'){ alert('Please enter a search'); return false; }";
    $form['search_block_form']['#attributes']['placeholder'] = t('Search');
}


/**
 * `Progress Bar on top of the Submission Form`
 * @method UTKdrupal_form_alter
 * @param  [type]               $form       Form Name
 * @param  [type]               $form_state Form State Name
 * @param  [type]               $form_id    Node ID for the form
 */

/*
 * Implementation of hook_form_alter()
 */
function UTKdrupal_form_alter(&$form, &$form_state, $form_id) {
  // Use this to debug the form
  // dsm($form_id);
  $possibleForms = array('xml_form_builder_ingest_form', 'islandora_scholar_pdf_upload_form');
  if(in_array($form_id, $possibleForms, true)) {
    $formStage = "progress-current";
    $formStage2 = "progress-todo";
    if($form_id == 'islandora_scholar_pdf_upload_form') {
      $formStage = "";
      $formStage2 = "progress-done progress-current";
    }
    $form['#prefix'] = '<div class="entityform-form-elements">
      <ol class="progress-track">
        <li class="progress-done">
          <center>
            <div class="icon-wrap">
              <svg class="icon-state icon-down-arrow" viewBox="0 0 512 512">
            <path d="m479 201c0 10-4 19-11 26l-186 186c-7 7-16 11-26 11c-10 0-19-4-26-11l-186-186c-7-7-11-16-11-26c0-10 4-19 11-26l21-21c8-7 17-11 26-11c11 0 19 4 26 11l139 139l139-139c7-7 15-11 26-11c9 0 18 4 26 11l21 21c7 8 11 16 11 26z"></path>
                <use xlink:href="#icon-check-mark"></use>
              </svg>
            </div>
            <span class="progress-text">Begin submission</span>
          </center>
        </li>
        <li class="progress-done ' . $formStage . '">
          <center>
            <div class="icon-wrap">
            <svg class="icon-state icon-down-arrow" viewBox="0 0 512 512">
          <path d="m479 201c0 10-4 19-11 26l-186 186c-7 7-16 11-26 11c-10 0-19-4-26-11l-186-186c-7-7-11-16-11-26c0-10 4-19 11-26l21-21c8-7 17-11 26-11c11 0 19 4 26 11l139 139l139-139c7-7 15-11 26-11c9 0 18 4 26 11l21 21c7 8 11 16 11 26z"></path>
              <use xlink:href="#icon-check-mark"></use>
            </svg>
            </div>
            <span class="progress-text">Description</span>
          </center>
        </li>
        <li class="' . $formStage2 . '">
          <center>
            <div class="icon-wrap">
            <svg class="icon-state icon-down-arrow" viewBox="0 0 512 512">
          <path d="m479 201c0 10-4 19-11 26l-186 186c-7 7-16 11-26 11c-10 0-19-4-26-11l-186-186c-7-7-11-16-11-26c0-10 4-19 11-26l21-21c8-7 17-11 26-11c11 0 19 4 26 11l139 139l139-139c7-7 15-11 26-11c9 0 18 4 26 11l21 21c7 8 11 16 11 26z"></path>
                <use xlink:href="#icon-check-mark"></use>
              </svg>
            </div>
            <span class="progress-text">Upload Files</span>
          </center>
        </li>
        <li class="progress-todo">
          <center>
            <div class="icon-wrap">
              <svg class="icon-state icon-check-mark">
                <use xlink:href="#icon-check-mark"></use>
              </svg>
            </div>
            <span class="progress-text">Submitted for Review</span>
          </center>
        </li>
      </ol>';
    }
  return $form;
}

/**
 * This needs to be deleted.
 * @method UTKdrupal_preprocess_islandora_large_image
 * @param  [type]                                     $variables [description]
 */
/**
 * Implements hook_preprocess().
 */
function UTKdrupal_preprocess_islandora_large_image(&$variables) {
  $variables['large_image_preprocess_function_variable'] = "TESTING LARGE IMAGE PREPROCESS FUNCTION IN THEME";
}

/**
 * Adds a placeholder attribute to the search query
 * @method UTKdrupal_form_islandora_solr_simple_search_form_alter
 * @param  [type]                                                 $form       Form Name
 * @param  [type]                                                 $form_state Form State Name
 * @param  [type]                                                 $form_id    Node ID for the Form
 */
/**
 * Implements hook_form_alter().
 */
function UTKdrupal_form_islandora_solr_simple_search_form_alter(&$form, &$form_state, $form_id) {
  $form['simple']['islandora_simple_search_query']['#attributes']['placeholder'] = t("Search Repository");
}


/**
 * From here, we can do something entirely different for this particular collection, based on PID
 * Editing or adding to the variables array here will make it available in the overridden template,
 * In this case, 'islandora-basic-collection-wrapper--islandora-sp-basic-image-collection.tpl.php'
 * @method UTKdrupal_preprocess_islandora_basic_collection_wrapper__islandora_sp_basic_image_collection
 * @param  [type]                                                                                       $variables [description]
 */
/**
 * Theme suggestion preprocess for islandora:sp_basic_image_collection
 */
function UTKdrupal_preprocess_islandora_basic_collection_wrapper__islandora_sp_basic_image_collection(&$variables) {
  $variables['template_preprocess_function_variable'] = "TESTING THE TEMPLATE PREPROCESS FUNCTION, UNIQUE TO BASIC IMAGE";
}

/**
 * Preprocess block based on delta.
 * @method UTKdrupal_preprocess_block
 * @param  [type]                     $variables [description]
 */

/**
 * Implements hook_preprocess_block().
 */
function UTKdrupal_preprocess_block(&$variables) {
  if ($variables['block']->{"delta"} == "simple") {
    $variables['classes_array'][] = 'fun-class';
    $variables['title_attributes_array']['class'] = array(
      'fun-title-attributes',
      'class-two-here',
    );
  }
}


/**
 * Render a block unique to this themes layouts.
 *
 * @param string $module
 *   The module providing the block.
 * @param string $delta
 *   The delta of the block
 *
 * @return string
 *   The rendered block's HTML content.
 */
function UTKdrupal_block_render($module, $delta, $as_renderable = FALSE) {
  $block = block_load($module, $delta);
  $block_content = _block_render_blocks(array($block));
  $build = _block_get_renderable_array($block_content);
  if ($as_renderable) {
    return $build;
  }
  $block_rendered = drupal_render($build);
  return $block_rendered;
}


/**
 * Hide fields from the user profile
 * @method UTKdrupal_preprocess_user_profile
 * @param  [type]                            $variables [description]
 */

 /**
  * Implements template_preprocess_page().
 */
function UTKdrupal_preprocess_user_profile(&$variables) {
  unset($variables['user_profile']['summary']['member_for']['#title']);
  unset($variables['user_profile']['summary']['member_for']['#markup']);
  unset($variables['user_profile']['summary']['member_for']['#type']);
  unset($variables['user_profile']['summary']['member_for']);
  $variables['user_profile']['summary']['#title']='';
}

/**
 * Implements hook_menu_local_tasks_alter().
 */
function UTKdrupal_menu_local_tasks_alter(&$data, $router_item, $root_path) {
  if (user_is_logged_in()) {
    // dpm(get_defined_vars());
    if ($root_path == 'user/%') {
      // Change the first tab title from 'View' to 'Profile'.
      if(isset($data['tabs'][0]) && is_array($data['tabs'][0])){
        foreach ($data['tabs'][0]['output'] as $key => $value) {
          if ($value['#link']['title'] == t('View')){
            $data['tabs'][0]['output'][$key]['#link']['title'] = t('Profile');
          }
          if ($value['#link']['title'] == t('Edit')){
            $data['tabs'][0]['output'][$key]['#link']['title'] = t('Edit Profile');
          }
        }
      }
    }
    $possibleUrls = array('islandora/object/%/manage/datastreams', 'islandora/object/%', 'islandora/object/%/manage');
    if (in_array($root_path, $possibleUrls, true)) {
      if(isset($data['tabs'][0]) && is_array($data['tabs'][0])){
        foreach ($data['tabs'][0]['output'] as $key => $value) {
          if ($value['#link']['title'] == t('Document')){
            unset($data['tabs'][0]['output'][$key]);
          }
          if ($value['#link']['title'] == t('Manage')){
            $data['tabs'][0]['output'][$key]['#link']['title'] = t('Manage Files');
            $data['tabs'][0]['output'][$key]['#link']['href'] = $router_item['href'] . '/manage/datastreams';
          }
        }
      }
    }
    global $user;
    if (in_array('authUser-role', $user->roles)) {
      if ($root_path == 'messages/new') {
        drupal_goto('/');
      }
    }
  }
  // Check if the user has the 'admin' role.
  global $user;
  if (in_array('administrator', $user->roles)) {
    //dpm(get_defined_vars());
  }
  if (in_array('authenticated user', $user->roles)) {
  //  dpm(get_defined_vars());
  //  dsm($form_id);
  }
}
