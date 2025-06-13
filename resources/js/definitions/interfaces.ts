import { Method, ModuleAvailableModals, StatusSlug, ToastType } from './types';

export interface PaginationResponse {
    current_page: number;
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: Object;
    next_page_url: string;
    path: string;
    per_page: number;
    prev_page_url: number | null;
    to: number;
    total: number;
}

export interface FlashResponse {
    message: string;
    title: string;
    type: ToastType;
}

export interface FilterProps {
    q?: string;
    order?: string;
    orderBy?: string;
    status?: string;
}

export interface CountProps {
    draft: number;
    published: number;
    deleted: number;
    all: number;
}

export interface StatusProps {
    label: string;
    value: string;
    count: number;
}

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
}

export interface SidebarProps {
    href: string;
    name: string;
    collapsed: boolean;
    method?: Method;
    as?: string;
    isDestructive?: boolean;
}

export interface PageHeaderProps {
    backLink: string;
    title: string;
}

export interface SearchBarProps {
    placeholder: string;
}

export interface StatusBarProps {
    statuses: StatusProps[];
    currentStatus: string;
}

export interface CreateItemProps {
    title: string;
    subtitle: string;
    buttonLink: string;
    buttonText: string;
}

export interface EmptyDataProps {
    title: string;
}

export interface TableHeaderProps {
    headings: string[];
    sizes: number[];
}

export interface TableRowProps {
    items: string[];
    link: string;
    viewTitle: string;
    sizes: number[];
}

export interface ModalLayoutProps {
    title: string;
    backLink: string;
}

export interface ChannelInfo {
    id: string;
    name?: string;
    profilePicture?: string;
    subscriberCount?: string;
    videoCount?: string;
    viewCount?: string;
}

/**
 * sidebar/PublishMenu.vue
 */
export interface PublishMenuProps {
    status: string;
    statusId: number;
    createNew: boolean;
    isDisabled: boolean;
}

/**
 * global/MetaTags.vue
 */
export interface MetaTagsProps {
    title: string;
}

/**
 * Toast Definitions
 */
export interface Toast {
    id: string;
    type: ToastType;
    title: string;
    message?: string;
    duration: number;
}

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
}

export interface ModulePaginationResponse extends PaginationResponse {
    data: Module[];
}

export interface ModulesIndexProps {
    modules: ModulePaginationResponse;
    counts: CountProps;
    filters: FilterProps;
    modal?: ModuleAvailableModals;
    connection?: YoutubeConnectionProps;
    flash: FlashResponse;
    channelData?: ChannelInfo;
}

export interface ModulesShowProps {
    module: Module;
    flash?: FlashResponse | undefined;
    errors?: ComponentErrorData;
}

export interface ModulesCreateProps {
    module?: Module;
    flash?: FlashResponse | undefined;
    errors?: ComponentErrorData;
}

export interface ComponentErrorData {
    errors: Object;
}

/**
 * Youtube Connection Definitions
 */
export interface YoutubeConnectionProps {
    authUrl: string | null;
    connected: boolean;
    channelData?: ChannelInfo;
}

export interface VideoGenerateProps {
    title: string;
    connection: YoutubeConnectionProps;
}

/**
 * Course Definitions
 */
export interface CoursePaginationResponse extends PaginationResponse {
    data: Course[];
}

export interface CoursesIndexProps extends FilterProps {
    courses: CoursePaginationResponse;
}

export interface ChannelInfoProps {
    channelInfo: ChannelInfo;
}

export interface ModuleComponentData {
    clearHistory: boolean;
    component: string;
    encryptHistory: boolean;
    props: ModulesShowProps;
    rememberedState: Object;
    url: string;
    version: string;
}
