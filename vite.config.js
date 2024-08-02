import { defineConfig } from "vite";
import sassGlobImports from "vite-plugin-sass-glob-import";
import laravel from "laravel-vite-plugin";

export default defineConfig({
  plugins: [
    laravel({
      input: ["resources/css/app.scss", "resources/js/app.js"],
      refresh: true,
    }),
    sassGlobImports(),
  ],
  css: {
    preprocessorOptions: {
      scss: {},
    },
  },
});
