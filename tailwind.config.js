import path from "path";
import  { twWithADUI } from "./wordpress/wp-content/themes/atelierdesign/ui/core/index";

export default twWithADUI({
  content: [
    path.resolve(__dirname, "./wordpress/wp-content/themes/atelierdesign/*.{html,php,js}"),
    path.resolve(__dirname, "./wordpress/wp-content/themes/atelierdesign/templates/*.{html,php,js}"),
    path.resolve(__dirname, "./wordpress/wp-content/themes/atelierdesign/components/**/*.{html,php,js}"),
    path.resolve(__dirname, "./wordpress/wp-content/themes/atelierdesign/ui/acf/components/**/*.{html,php,js}"),
  ],
  theme: {
    extend: {
      fontFamily: {
        serif: [
          'IvyJournal',
          'ui-serif',
          'Georgia',
          'Cambria',
          'Times New Roman',
          'Times',
          'serif',
        ],
        sans: [
          'Manrope',
          'ui-sans-serif',
          'system-ui',
          'sans-serif',
        ],
        
        fontWeight: {
          light: '300',    // Ivy Journal Light
          normal: '400',   // Ivy Journal Regular
        },
      },
    },
  },
  corePlugins: {
    preflight: true,
  },
});
