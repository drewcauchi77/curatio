import type { DefineComponent } from 'vue';

export type ImportMetaData = Record<string, () => Promise<{ default: DefineComponent }>>;

export type Method = 'get' | 'post' | 'put' | 'patch' | 'delete';

export type CreateModuleForm = {
    title: string;
    description: string;
    status_id: number;
};

export type LoginForm = {
    email: string;
    password: string;
};

export type RegisterForm = {
    fullName: string;
    email: string;
    password: string;
    password_confirmation: string;
    companyName: string;
};

/**
 * Toast Types
 */
export type ToastType = 'success' | 'error' | 'info';

/**
 * Module Types
 */
export type ModuleAvailableModals = 'VideoGenerateModal';

export type StatusSlug = 'published' | 'draft' | 'deleted';