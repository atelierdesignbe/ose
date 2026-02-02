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
        // serif: ['var(--font-serif)'],
        // sans: ['var(--font-sans)'],
      },
    },
  },
});
