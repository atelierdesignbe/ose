<?php

/**
 * LANG 
 */
function display_lang() {
  if (
      ! function_exists( 'pll_current_language' ) ||
      ! function_exists( 'pll_the_languages' )
  ) {
      return;
  }

  $current_lang = pll_current_language( 'slug' );

  $languages = pll_the_languages( array(
      'show_flags' => 0,
      'show_names' => 1,
      'hide_if_empty' => 0,
      'display_names_as' => 'slug',
      'raw' => 1
  ) );


  echo '<div class="lang autoscale">';
    echo '<button type="button" class="lang-btn button button-none" js-expand-button><span class="button-title">'. $current_lang. '</span></button>';
    echo '<div class="modal">';
    echo '<ul class="lang-list modal-expand" js-expand>';
    foreach($languages as $lang): 
      $isActive = $lang['current_lang'] ? 'is-active' : '';
      echo '<li js-expand-item><a href="'.$lang['url'].'" class="button button-none '.$isActive.'"><span class="button-title">'.$lang['slug'].'</span></a></li>';
    endforeach;
    echo '</ul>';
    echo '</div>';
  echo '</div>';
  }


function icon($type, $class) {
  $icon = '';
  switch($type) {
    case 'chevron': 
      $icon = '<svg class="'.$class.'" viewBox="0 0 11 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.1058 0.353665L5.22968 5.22979L0.353549 0.353665"/></svg>'; 
      break;
    case 'facebook':
      $icon = '<svg class="'.$class.'" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M10.6667 22V12.5882H7V9.05882H10.6667V7.16353C10.6667 3.57529 12.4874 2 15.4638 2C16.8893 2 17.6431 2.10588 18 2.15412V5.52941H15.9699C14.7065 5.52941 14.3333 6.19765 14.3333 7.55059V9.05882H17.9683L17.4658 12.5882H14.3333V22H10.6667Z" /></svg>'; 
      break;
    case 'twitter': 
      $icon = '<svg class="'.$class.'" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M17.6874 3.0625L12.6907 8.77425L8.37045 3.0625H2.11328L9.58961 12.8387L2.50378 20.9375H5.53795L11.0068 14.6886L15.7863 20.9375H21.8885L14.095 10.6342L20.7198 3.0625H17.6874ZM16.6232 19.1225L5.65436 4.78217H7.45745L18.3034 19.1225H16.6232Z"></path></svg>'; 
      break;
    case 'linkedin': 
      $icon = '<svg class="'.$class.'" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M9.237 8.855v12.139h3.769v-6.003c0-1.584.298-3.118 2.262-3.118 1.937 0 1.961 1.811 1.961 3.218v5.904H21v-6.657c0-3.27-.704-5.783-4.526-5.783-1.835 0-3.065 1.007-3.568 1.96h-.051v-1.66H9.237zm-6.142 0H6.87v12.139H3.095z"></path></svg>'; 
      break;
    case 'arrow': 
      $icon = '<svg class="'.$class.'" viewBox="0 0 17 21" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M16.2803 15.5303C16.5732 15.2374 16.5732 14.7626 16.2803 14.4697L11.5074 9.6967C11.2145 9.40381 10.7396 9.40381 10.4467 9.6967C10.1538 9.98959 10.1538 10.4645 10.4467 10.7574L14.6893 15L10.4467 19.2426C10.1538 19.5355 10.1538 20.0104 10.4467 20.3033C10.7396 20.5962 11.2145 20.5962 11.5074 20.3033L16.2803 15.5303ZM0.75 15H0V15.75H0.75V15ZM15.75 15V14.25H0.75V15V15.75H15.75V15ZM0.75 15H1.5V0H0.75H0V15H0.75Z" /></svg>'; 
      break;
    default: 
      $icon = '';
      break;
  }

  echo $icon;
}


function wysiwyg($key, $headingKey = null, $paragraphKey = null) {
  $heading = [
        [
          'key' => 'heading-2xl',
          'text' => 'Extra Extra Large',
          'format' => ['block' => 'h2', 'classes' => 'heading-2xl'],
          'disabled_buttons' => ['custom-bold', 'custom-italic', 'custom-underline', 'custom-bullist', 'custom-numlist'],
        ],
        [
          'key' => 'heading-xl',
          'text' => 'Extra Large',
          'format' => ['block' => 'h3', 'classes' => 'heading-xl'],
          'disabled_buttons' => ['custom-bold', 'custom-italic', 'custom-underline', 'custom-bullist', 'custom-numlist'],
        ],
        [
          'key' => 'heading-lg',
          'text' => 'Large',
          'format' => ['block' => 'h4', 'classes' => 'heading-lg'],
          'disabled_buttons' => ['custom-bold', 'custom-italic', 'custom-underline', 'custom-bullist', 'custom-numlist'],
        ],
        [
          'key' => 'heading-md',
          'text' => 'Medium',
          'format' => ['block' => 'h5', 'classes' => 'heading-md'],
          'disabled_buttons' => ['custom-bold', 'custom-italic', 'custom-underline', 'custom-bullist', 'custom-numlist'],
        ],
        [
          'key' => 'heading-sm',
          'text' => 'Small',
          'format' => ['block' => 'h6', 'classes' => 'heading-sm'],
          'disabled_buttons' => ['custom-bold', 'custom-italic', 'custom-underline', 'custom-bullist', 'custom-numlist'],
        ],
      ];
  
      $paragraph = [
        [
          'key' => 'paragraph-xl',
          'text' => 'Extra Large',
          'format' => ['block' => 'p', 'classes' => 'paragraph-xl'],
        ],
        [
          'key' => 'paragraph-lg',
          'text' => 'Large',
          'format' => ['block' => 'p', 'classes' => 'paragraph-lg'],
        ],
        [
          'key' => 'paragraph-md',
          'text' => 'Medium',
          'format' => ['block' => 'p', 'classes' => 'paragraph-md'],
          'default' => true,
        ],
        [
          'key' => 'paragraph-sm',
          'text' => 'Small',
          'format' => ['block' => 'p', 'classes' => 'paragraph-sm'],
        ],
      ];

    if (is_array($headingKey) && !empty($headingKey)) {
        $heading = array_filter($heading, function($item) use ($headingKey) {
            return in_array($item['key'], $headingKey);
        });
        $heading = array_values($heading);
    }

    if (is_array($paragraphKey) && !empty($paragraphKey)) {
        $paragraph = array_filter($paragraph, function($item) use ($paragraphKey) {
            return in_array($item['key'], $paragraphKey);
        });
        $paragraph = array_values($paragraph);
    }

  return array(
    'key' => $key,  
    'label' => '',
    'name' => 'content',
    'type' => 'wysiwyg',
    'media_upload' => 0,
    'tabs' => 'visual',
    'acfe_wysiwyg_min_height' => 70,
    'acfe_wysiwyg_max_height' => '',
    // 'acfe_wysiwyg_valid_elements' => 'h2,h2[class],h3,h3[class],h4,h4[class],h5,h5[class],h6,h6[class],p,p[class],ul,ul[class],ol,ol[class],li,li[class],a,a[class],br,em,em[class],i,i[class],b,b[class],strong,strong[class],span,span[class],mark,mark[class]',
    'acfe_wysiwyg_custom_style' => '',
    'acfe_wysiwyg_disable_wp_style' => 0,
    'acfe_wysiwyg_autoresize' => 1,
    'acfe_wysiwyg_disable_resize' => 1,
    'acfe_wysiwyg_remove_path' => 1,
    'acfe_wysiwyg_menubar' => 0,
    'acfe_wysiwyg_transparent' => 0,
    'acfe_wysiwyg_merge_toolbar' => 0,
    'acfe_wysiwyg_custom_toolbar' => 0,
    'acfe_wysiwyg_auto_init' => 0,
    'acfe_wysiwyg_height' => 70,
    'acfe_wysiwyg_toolbar_buttons' => [
    'acfe_wysiwyg_toolbar_1' => [
        [
          'acfe_wysiwyg_toolbar_row' => 'typography-selector',
        ],
        [
          'acfe_wysiwyg_toolbar_row' => 'seo-tag-selector',
        ],
        [
          'acfe_wysiwyg_toolbar_row' => 'custom-bold',
        ],
        [
          'acfe_wysiwyg_toolbar_row' => 'custom-italic',
        ],
        [
          'acfe_wysiwyg_toolbar_row' => 'heading-highlight',
        ],
        [
          'acfe_wysiwyg_toolbar_row' => 'custom-align-left',
        ],
        [
          'acfe_wysiwyg_toolbar_row' => 'custom-align-center',
        ],
        [
          'acfe_wysiwyg_toolbar_row' => 'custom-align-right',
        ],
        // [
        //   'acfe_wysiwyg_toolbar_row' => 'custom-align-justify',
        // ],
        [
          'acfe_wysiwyg_toolbar_row' => 'balance-text',
        ],
        [
          'acfe_wysiwyg_toolbar_row' => 'custom-bullist',
        ],
        [
          'acfe_wysiwyg_toolbar_row' => 'custom-numlist',
        ],
        [
          'acfe_wysiwyg_toolbar_row' => 'link',
        ],
      ],
    ],
    'ad_typography_config' => [
      [
        'text' => 'Heading',
        'items' => $heading
      ],
      [
        'text' => 'Paragraph',
        'items' => $paragraph
      ],
    ]
  );
}
?>