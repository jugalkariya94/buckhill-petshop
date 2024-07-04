type ValidationRule = (value: string) => true | string;

export const passwordRules: ValidationRule[] = [
    (value: string) => {
        if (value) return true;
        return 'Password is required.';
    },
];
