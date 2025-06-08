// @ts-check

import eslint from '@eslint/js';
import tseslint from 'typescript-eslint';
import pluginVue from 'eslint-plugin-vue';
import globals from 'globals'

export default tseslint.config(
    eslint.configs.recommended,
    ...tseslint.configs.recommended,
    ...pluginVue.configs['flat/recommended'],
    {
        files: ['resources/**/*.{js,ts,vue}'],
        ignores: [
            'vendor/**/*', 
            'node_modules/**/*'
        ],
        rules: {
            // override/add rules settings here, such as:
            // 'vue/no-unused-vars': 'error'
        },
        languageOptions: {
            sourceType: 'module',
            globals: {
                ...globals.browser
            }
        }
    }
);