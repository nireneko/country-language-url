<?php

namespace Drupal\country_language_url\Service;

use Symfony\Component\HttpFoundation\Request;

class CountryRedirect implements CountryRedirectInterface {

  public function __construct(
    protected InboundPathProcessorInterface $pathProcessor,
    protected CountryInterface $country
  ) {}

  /**
   * @todo: develop this.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *
   * @return string|void
   */
  public function addCountryToUrlFromRequest(Request $request) {
    // Based con the code of smart_ip_locale_redirect module.

    // Get URL info and process it to be used for hash generation.
    parse_str($request->getQueryString(), $request_query);

    if (strpos($request->getPathInfo(), '/sites/default/files/') === 0) {
      // If the request for a file then do nothing.
      return;
    }
    elseif ($request->getPathInfo() == '/' || $request->getPathInfo() == '') {
      $path = '';
    }
    else {
      // Do the inbound processing so that for example language prefixes are
      // removed.
      $path = $this->pathProcessor->processInbound($request->getPathInfo(), $request);
    }

    try {

      $langcode = 'es';
      $country_code = 'es';

      if ($current_language_id == $langcode && ($request->getPathInfo() != '/' && $request->getPathInfo() != '')) {
        // Exit the loop to prevent too many redirects error.
        return;
      }

      if ($request->getPathInfo() != '/' && $update_hl == '') {
        // ar-jo => ar.
        $old_prefix = substr($langcode, 0, 2);
        // ar-jo => jo.
        // $old_suffix = substr($langcode, 2);
        // en-us => en.
        $new_prefix = substr($current_language_id, 0, 2);
        // en-us  => us.
        // $new_suffix = substr($current_language_id, 2);
        // To check if the request has language prefix.
        if ($old_prefix != $new_prefix && in_array(explode('/', $request->getPathInfo())[1], $languages)) {
          // ar-jo becomes en-jo.
          $replaced_langcode = substr_replace($langcode, $new_prefix, 0, 2);
          if (in_array($replaced_langcode, $languages)) {
            $langcode = $replaced_langcode;
          }
          else {
            // In case en-us there is no ar-us so redirect to ar.
            $new_langcode = substr($replaced_langcode, 0, 2);
            if (in_array($new_langcode, $languages)) {
              $langcode = $new_langcode;
            }
          }
        }

      }

      $query = $request->getQueryString();
      $origin_url = $request->getSchemeAndHttpHost() . $request->getBaseUrl();
      if ($request->getPathInfo() == '/' || $request->getPathInfo() == '') {
        $url = $origin_url . '/' . $langcode . $path;
      }
      else {
        $url = $origin_url . '/' . $langcode . $this->aliasManager->getAliasByPath($path, $langcode);
      }

      // Check if there is a query string then reserve it.
      if (!empty($query)) {
        $url = $url . '?' . $query;
      }

      return $url;
    }

  }
}
