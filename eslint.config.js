import js from '@eslint/js'
import eslintPluginVue from 'eslint-plugin-vue'
import ts from 'typescript-eslint'

export default ts.config(
    js.configs.recommended,
    ...ts.configs.recommended,
    ...ts.configs.stylistic,
    ...eslintPluginVue.configs['flat/recommended'],
    {
        languageOptions: {
            parserOptions: {
                parser: ts.parser
            }
        },
        settings: {
            // This will do the trick
            'import/parsers': {
                espree: ['.js', '.cjs', '.mjs', '.jsx'],
                '@typescript-eslint/parser': ['.ts', '.tsx'],
                'vue-eslint-parser': ['.vue'],
            },
            'import/resolver': {
                typescript: true,
                node: true,
                alias: {
                    map: [
                        ['@', './resources/js'],
                        ['~', './node_modules'],
                    ],
                    extensions: ['.js', '.ts', '.jsx', '.tsx', '.vue'],
                },
            },
            vite: {
                configPath: './vite.config.ts',
            },
        },
    },

)
