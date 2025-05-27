import { Method, ModuleAvailableModals, StatusSlug, ToastType } from "./types";

export interface PaginationResponse {
    current_page: number;
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: any;
    next_page_url: string;
    path: string;
    per_page: number;
    prev_page_url: number | null;
    to: number;
    total: number;
};

export interface FlashResponse {
    message: string;
    success: boolean;
};

export interface FilterProps {
    q?: string;
    order?: string;
    orderBy?: string;
    status?: string;
};

export interface CountProps {
    drafts: number;
    published: number;
    trash: number;
    all: number;
};

export interface StatusProps {
    label: string;
    value: string;
    count: number;
};

export interface Course {
    id: string;
    title: string;
    status: string;
    created_at: string;
    updated_at: string;
}

export interface InputFieldProps {
    inputName: string;
    labelName: string;
    inputType: string;
    placeholder: string;
};

export interface SidebarProps {
    href: string;
    name: string;
    collapsed: boolean;
    method?: Method;
    as?: string;
    isDestructive?: boolean;
};

export interface PageHeaderProps {
    backLink: string;
    title: string;
};

export interface SearchBarProps {
    placeholder: string;
};

export interface StatusBarProps {
    statuses: StatusProps[];
    currentStatus: string;
}

export interface CreateItemProps {
    title: string;
    subtitle: string;
    buttonLink: string;
    buttonText: string;
};

export interface EmptyDataProps {
    title: string;
};

export interface TableHeaderProps {
    headings: string[];
    sizes: number[];
};

export interface TableRowProps {
    items: string[];
    link: string;
    viewTitle: string;
    sizes: number[];
};

/**
 * sidebar/PublishMenu.vue
 */
export interface PublishMenuProps {
    status: string;
    statusId: number;
    createNew: boolean;
    isDisabled: boolean;
};

/**
 * Toast Definitions
 */
export interface Toast {
    id: string;
    type: ToastType;
    title: string;
    description?: string;
    duration: number;
};

/**
 * Module Definitions
 */
export interface Module {
    id: string;
    title: string;
    description: string;
    status: string;
    created_at: string;
    updated_at: string;
    status_slug: StatusSlug;
    status_id: number;
};

export interface ModulePaginationResponse extends PaginationResponse {
    data: Module[];
};

export interface ModulesIndexProps extends FilterProps {
    modules: ModulePaginationResponse;
    counts: CountProps;
    modal?: ModuleAvailableModals;
    flash: FlashResponse;
};

export interface ModulesShowProps {
    module: Module;
};

export interface ModulesCreateProps {
    module?: Module;
};

/**
 * Course Definitions
 */
export interface CoursePaginationResponse extends PaginationResponse {
    data: Course[]
}

export interface CoursesIndexProps extends FilterProps {
    courses: CoursePaginationResponse;
}