import { createVuetify } from "vuetify";
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";

const BuckhillGreen = {
    dark: false,
    colors: {
        background: '#FFFFFF',
        surface: '#FFFFFF',
        'surface-variant': '#424242',
        'on-surface-variant': '#EEEEEE',
        primary: '#4EC690',
        'primary-darken-1': '#3700B3',
        secondary: '#03DAC6',
        'secondary-darken-1': '#018786',
        error: '#B00020',
        info: '#1976D2',
        success: '#4CAF50',
        warning: '#FB8C00',
        light: '#EDF5F1',
        dark: '#2DB479',
        mainInfo: '#2196F3',
        grey: '#B6C4C1',
        'grey-200': '#ECF0EF',
        'grey-300': '#DDE3E2',
        'grey-500': '#95A7A3',
        'grey-600': '#69817B',
    },
    variables: {
        'border-color': '#000000',
        'border-opacity': 0.12,
        'high-emphasis-opacity': 0.87,
        'medium-emphasis-opacity': 0.60,
        'disabled-opacity': 0.38,
        'idle-opacity': 0.04,
        'hover-opacity': 0.04,
        'focus-opacity': 0.12,
        'selected-opacity': 0.08,
        'activated-opacity': 0.12,
        'pressed-opacity': 0.12,
        'dragged-opacity': 0.08,
        'theme-kbd': '#212529',
        'theme-on-kbd': '#FFFFFF',
        'theme-code': '#F5F5F5',
        'theme-on-code': '#000000',
    }
}


const vuetify = createVuetify({
    components,
    directives,
    theme: {
        defaultTheme: 'BuckhillGreen',
            themes: {
            BuckhillGreen,
        },
    },
});

export default vuetify;
