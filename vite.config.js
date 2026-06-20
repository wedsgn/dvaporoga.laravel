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
  server: {
    // Страница отдаётся nginx по http://localhost, поэтому и Vite должен
    // светить ассеты/HMR с того же origin (localhost), иначе localhost vs
    // 127.0.0.1 = разные origin и HMR может срываться в полную перезагрузку.
    host: "localhost",
    hmr: {
      host: "localhost",
    },
  },
  css: {
    preprocessorOptions: {
      scss: {
        api: "modern-compiler",
      },
    },
  },
});
