type ValidationRule = (value: string) => true | string;

export const nameRules: ValidationRule[] = [
    (value: string) => {
        if (value) return true;
        return 'Name is required.';
    },
    (value: string) => {
        if (value?.length <= 10) return true;
        return 'Name must be less than 10 characters.';
    }
];
