const defaultTheme = require("tailwindcss/defaultTheme");

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./app/**/*.{view.php,html,js}"],
  theme: {
    extend: {
      colors: {
        "tempest-blue": {
          500: "#0057BE",
          600: "#003D84",
        },
        //
        // Blues from the Tempest logo
        //
        // "tempest-blue": {
        //   400: "#00E7FF",
        //   500: "#29ABE2",
        //   600: "#0071BC",
        //   950: "#1B1429"
        // }
      },
      fontFamily: {
        display: ["Archivo", ...defaultTheme.fontFamily.sans],
        mono: ["Source Code Pro", ...defaultTheme.fontFamily.mono],
      },
      typography: {
        DEFAULT: {
          css: {
            pre: {
              fontWeight: "500",
            },
          },
        },
      },
    },
  },
  mode: "jit",
  plugins: [require("@tailwindcss/typography")],
};
