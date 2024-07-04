type ValidationRule = (value: string) => true | string;

export const emailRules: ValidationRule[] = [
    (value: string) => {
        if (value) return true;
        return 'Email is required.';
    },
    (value: string) => {
        if (/.+@.+\..+/.test(value)) return true;
        return 'E-mail must be valid.'
    }
];
