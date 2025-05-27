declare module '@vue/runtime-core' {
    interface ComponentCustomProperties {
        $t: (key: string, ...args: [string, string][]) => string;
    }
}

export {};