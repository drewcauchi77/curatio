import js from '@eslint/js';
import vue from 'eslint-plugin-vue';
import typescript from '@typescript-eslint/eslint-plugin';
import typescriptParser from '@typescript-eslint/parser';
import vueParser from 'vue-eslint-parser';
import prettier from 'eslint-plugin-prettier';
import prettierConfig from 'eslint-config-prettier';
import globals from 'globals';

export default [
    js.configs.recommended,
    ...vue.configs['flat/recommended'],
    {
        files: ['resources/js/**/*.{js,ts,vue}'],
        languageOptions: {
            ecmaVersion: 2022,
            sourceType: 'module',
            parser: vueParser,
            parserOptions: {
                parser: typescriptParser,
                ecmaFeatures: {
                    jsx: true,
                },
            },
            globals: {
                ...globals.browser,
                console: 'readonly',
                process: 'readonly',
                window: 'readonly',
                document: 'readonly',
                navigator: 'readonly',
                localStorage: 'readonly',
                sessionStorage: 'readonly',
            },
        },
        plugins: {
            vue,
            '@typescript-eslint': typescript,
            prettier,
        },
        rules: {
            'prettier/prettier': 'error',
            'vue/multi-word-component-names': 'off',
            'vue/no-v-html': 'warn',
            'vue/require-default-prop': 'off',
            'vue/require-explicit-emits': 'error',
            'vue/component-definition-name-casing': ['error', 'PascalCase'],
            'vue/component-name-in-template-casing': ['error', 'PascalCase'],
            'vue/custom-event-name-casing': ['error', 'camelCase'],
            'vue/define-macros-order': [
                'error',
                {
                    order: ['defineOptions', 'defineProps', 'defineEmits', 'defineSlots'],
                },
            ],
            'vue/no-empty-component-block': 'error',
            'vue/no-multiple-objects-in-class': 'error',
            'vue/no-static-inline-styles': 'warn',
            'vue/prefer-separate-static-class': 'error',
            'vue/prefer-true-attribute-shorthand': 'error',
            'vue/v-for-delimiter-style': ['error', 'in'],
            '@typescript-eslint/no-unused-vars': [
                'error',
                {
                    argsIgnorePattern: '^_',
                    varsIgnorePattern: '^_',
                    caughtErrorsIgnorePattern: '^_',
                },
            ],
            '@typescript-eslint/no-explicit-any': 'warn',
            '@typescript-eslint/no-inferrable-types': 'error',
            '@typescript-eslint/ban-ts-comment': [
                'error',
                {
                    'ts-expect-error': 'allow-with-description',
                    'ts-ignore': 'allow-with-description',
                    'ts-nocheck': 'allow-with-description',
                    'ts-check': 'allow-with-description',
                },
            ],
            'no-console': process.env.NODE_ENV === 'production' ? 'error' : 'warn',
            'no-debugger': process.env.NODE_ENV === 'production' ? 'error' : 'warn',
            'no-var': 'error',
            'prefer-const': 'error',
            'prefer-template': 'error',
            'object-shorthand': 'error',
            'no-useless-concat': 'error',
            'no-useless-return': 'error',
            'no-multiple-empty-lines': ['error', { max: 1 }],
            'no-trailing-spaces': 'error',
            'comma-dangle': ['error', 'always-multiline'],
            'semi': ['error', 'always'],
            'quotes': ['error', 'single', { avoidEscape: true }],
            'indent': 'off',
            'linebreak-style': 'off',
            'max-len': 'off',
            'no-mixed-spaces-and-tabs': 'off',
            'no-tabs': 'off',
            'vue/html-indent': 'off',
            'vue/max-attributes-per-line': 'off',
            'vue/singleline-html-element-content-newline': 'off',
            'vue/multiline-html-element-content-newline': 'off',
            'vue/html-closing-bracket-newline': 'off',
            'vue/html-closing-bracket-spacing': 'off',
            'vue/html-self-closing': 'off',
        },
    },
    {
        files: ['resources/js/**/*.ts', 'resources/js/**/*.tsx'],
        languageOptions: {
            parser: typescriptParser,
        },
        plugins: {
            '@typescript-eslint': typescript,
        },
        rules: {
            '@typescript-eslint/no-unused-vars': [
                'error',
                {
                    argsIgnorePattern: '^_',
                    varsIgnorePattern: '^_',
                    caughtErrorsIgnorePattern: '^_',
                },
            ],
            '@typescript-eslint/no-explicit-any': 'warn',
            '@typescript-eslint/no-inferrable-types': 'error',
        },
    },
    {
        files: ['resources/js/**/*.vue'],
        languageOptions: {
            parser: vueParser,
            parserOptions: {
                parser: typescriptParser,
            },
        },
        rules: {
            'indent': 'off',
            '@typescript-eslint/indent': 'off',
        },
    },
    {
        files: ['resources/js/**/*.js'],
        rules: {
            '@typescript-eslint/no-var-requires': 'off',
        },
    },
    prettierConfig,
    {
        ignores: [
            'node_modules/**',
            'dist/**',
            'build/**',
            'public/**',
            'vendor/**',
            'bootstrap/**',
            'storage/**',
            '*.d.ts',
            'vite.config.ts',
            'tailwind.config.js',
            'postcss.config.js',
        ],
    },
];