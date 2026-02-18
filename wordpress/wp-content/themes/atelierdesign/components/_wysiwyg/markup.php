<?php

/**
 * Safelist used classNames:
 * - text-left
 * - text-center
 * - text-right
 * - max-md:text-left
 * - max-md:text-center
 * - max-md:text-right
 * - text-balance
 * - block
 * - uppercase
 */
?>
<?php
$isFullWidth = isset($args['layout_settings']['isFullWidth']) ? $args['layout_settings']['isFullWidth'] : false;
if (isset($args['isNested']) && $args['isNested'] == true) {
  $isFullWidth = true;
}
?>
<?php if (!empty($args['content'])): ?>
  <div class="wysiwyg <?= $args['inside'] ? '' : 'my-elem-md' ?> <?= $isFullWidth ? '' : 'px-content' ?> autoscale-children">
    <?php
    $content = $args['content'] ?? '';

    // just <p> tags should be converted to <p class="paragraph-md">
    $content = preg_replace('/<p>/', '<p class="paragraph-md">', $content);

    // <h1-6> <p> <div> <ul> <ol> append "aos animate-fadeinup"
    $libxmlPreviousState = libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $wrapperId = 'wysiwyg-temp-' . uniqid();
    $dom->loadHTML(
      '<?xml encoding="utf-8" ?><div id="' . $wrapperId . '">' . $content . '</div>',
      LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
    );

    $targets = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'li', 'hr'];
    foreach ($targets as $tag) {
      $nodes = $dom->getElementsByTagName($tag);
      foreach ($nodes as $node) {
        if (!$node->hasAttribute('class')) {
          continue;
        }

        if ($tag === 'li') {
          $ancestor = $node->parentNode;
          $hasLiAncestor = false;
          while ($ancestor instanceof DOMElement) {
            if (strtolower($ancestor->nodeName) === 'li') {
              $hasLiAncestor = true;
              break;
            }
            $ancestor = $ancestor->parentNode;
          }

          if ($hasLiAncestor) {
            continue;
          }
        }

        $classes = preg_split('/\s+/', trim($node->getAttribute('class')));
        $classes = $classes ?: [];

        if (!in_array('aos', $classes, true)) {
          $classes[] = 'aos';
        }

        if (!in_array('animate-fadeinup', $classes, true)) {
          $classes[] = 'animate-fadeinup';
        }

        $node->setAttribute('class', implode(' ', $classes));
      }
    }

    $xpath = new DOMXPath($dom);
    $wrapper = $xpath->query('//*[@id="' . $wrapperId . '"]')->item(0);
    $updatedContent = '';
    if ($wrapper) {
      foreach ($wrapper->childNodes as $child) {
        $updatedContent .= $dom->saveHTML($child);
      }
    }

    libxml_clear_errors();
    libxml_use_internal_errors($libxmlPreviousState);

    if (!empty($updatedContent)) {
      $content = $updatedContent;
    }

    echo $content;
    ?>
  </div>
<?php endif; ?>