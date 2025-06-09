import en from '../translations/en.json';

const getNestedTranslation = (obj, key: string): string[] => {
    return key.split('.').reduce((res, prop) => (res ? res[prop] : null), obj);
};

const replacePlaceholders = (text: string, replacements: [string, string][]): string => {
    replacements.forEach(([placeholder, value]) => {
        const regex = new RegExp(`\\[${placeholder}\\]`, 'g');
        text = text.replace(regex, value);
    });

    return text;
};

export default {
    install: (app) => {
        app.config.globalProperties.$t = (key: string, ...replacements: [string, string][]): string => {
            const translation = getNestedTranslation(en, key);
            if (!translation || typeof translation !== 'string') return key;
            return replacePlaceholders(translation, replacements);
        };
    },
};
