<template>

  <div class="flex flex-col w-full bg-white shadow-sm sm:border-t border-b border-border-light">
    <header :class="[authenticated ? 'justify-between' : 'justify-end']" class="sm:px-4 w-full min-h-[4rem] flex items-center px-12">

        <!-- Burger -->
        <svg v-if="authenticated" class="hidden xl:inline w-6 h-6 stroke-subtitle stroke-[1.5]" @click="toggleNavigation" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>

        <!-- Search field -->
        <div v-if="authenticated" class="sm:pl-12 sm:justify-end flex flex-row-reverse items-center justify-center gap-4 h-full grow">
            <input ref="searchInput" @focusout="toggleInputFocus(false)" @focus="toggleInputFocus(true)" class="sm:w-[24px] peer h-full selection:text-white selection:bg-brand-primary focus:outline-none placeholder-subtitle grow" type="text" placeholder="Search... (CTRL + K)" />
            <svg class="sm:hidden peer-focus:fill-brand-primary fill-subtitle transition-all duration-150 scale-90 peer-focus:scale-100" width="19" height="19" viewBox="0 0 19 19" xmlns="http://www.w3.org/2000/svg">
                <path d="M8 16C9.77498 15.9996 11.4988 15.4054 12.897 14.312L17.293 18.708L18.707 17.294L14.311 12.898C15.405 11.4997 15.9996 9.77544 16 8C16 3.589 12.411 0 8 0C3.589 0 0 3.589 0 8C0 12.411 3.589 16 8 16ZM8 2C11.309 2 14 4.691 14 8C14 11.309 11.309 14 8 14C4.691 14 2 11.309 2 8C2 4.691 4.691 2 8 2Z" />
            </svg>
        </div>

        <!-- Reminders -->
        <div v-if="authenticated" class="cursor-pointer sm:hidden relative w-12 h-12 rounded-full flex items-center justify-center group">

            <div>
                <BellIcon v-if="! viewRemindersForm" @click="toggleRemindersFormView" class="hover:animate-ring hover:scale-110 hover:stroke-2 w-6 h-6 stroke-subtitle transition-transform duration-150" />
                <BellIconSolid v-else class="w-7 h-7 fill-subtitle" />
            </div>

            <div @click="toggleRemindersFormView" v-if="viewRemindersForm" class="flex flex-col overflow-hidden absolute right-0 -mr-3 -mb-3 z-50 bottom-0 translate-y-full animate-appear-down bg-white border border-border-light shadow-sm rounded-md">

                <ul ref="remindersForm" class="list scrollbar-mobile w-96 max-h-[350px] overflow-overlay min-h-[3rem] flex flex-col">

                    <RouterLink to="/new/books" class="grid grid-cols-7 px-4 pb-2.5 pt-4 gap-2 focus:outline-none hover:bg-wallpaper/75 transition-colors duration-150 group/quick-item">

                        <div class="grid place-items-center col-span-1 min-w-[1.75rem] w-9 h-9 rounded-full bg-green-100 group-hover/quick-item:bg-brand-secondary transition-colors duration-150">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="transition-colors duration-150 group-hover/quick-item:stroke-brand-primary stroke-green-600 w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                            </svg>

                        </div>

                        <div class="col-span-6 flex flex-col gap-1">
                            <p class="font-medium group-hover/quick-item:text-brand-primary transition-colors duration-150">New Book</p>
                            <p class="text-subtitle">
                                Register a new book into inventory.
                                Go to "New Transaction" instead to add new book with first purchase
                            </p>
                        </div>

                    </RouterLink>

                    <RouterLink to="/new/transactions" class="quick-item grid grid-cols-7 px-4 pt-2.5 gap-2 focus:outline-none hover:bg-wallpaper/75 transition-colors duration-150 group/quick-item">

                        <div class="grid place-items-center col-span-1 min-w-[1.75rem] w-9 h-9 rounded-full bg-yellow-100 group-hover/quick-item:bg-brand-secondary transition-colors duration-150">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="transition-colors duration-150 group-hover/quick-item:stroke-brand-primary stroke-yellow-600 w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                            </svg>

                        </div>

                        <div class="col-span-6 flex flex-col gap-1">
                            <p class="font-medium group-hover/quick-item:text-brand-primary transition-colors duration-150">New Transaction</p>
                            <p class="text-subtitle">
                                Make a purchase or sale transaction of a new or existing item. Sale can only be made
                                from a primary store
                            </p>
                        </div>

                    </RouterLink>

                </ul>

            </div>

        </div>

        <!-- Quick Actions -->
        <div v-if="authenticated" class="cursor-pointer sm:hidden relative w-12 h-12 rounded-full flex items-center justify-center group">

            <!--            <svg :class="[viewQuickActionsMenu ? 'scale-110 stroke-black stroke-[1.25]' : 'stroke-[1.5] stroke-subtitle']" @click="toggleQuickActionsView" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="transition-transform duration-300 group-hover:scale-110 group-hover:stroke-black group-hover:stroke-[1.25] w-6 h-6">-->
            <!--                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />-->
            <!--            </svg>-->
            <div>
                <EllipsisHorizontalCircleIcon v-if="! viewQuickActionsMenu" @click="toggleQuickActionsView" class="hover:scale-110 hover:stroke-2 w-6 h-6 stroke-subtitle transition-transform duration-150" />
                <EllipsisHorizontalCircleIconSolid v-else class="w-7 h-7 fill-subtitle" />
            </div>

            <div @click="toggleQuickActionsView" v-if="viewQuickActionsMenu" class="flex flex-col overflow-hidden absolute right-0 -mr-3 -mb-3 z-50 bottom-0 translate-y-full animate-appear-down bg-white border border-border-light shadow-sm rounded-md">

                <ul ref="quickActionsMenu" class="list scrollbar-mobile w-96 max-h-[350px] overflow-overlay min-h-[3rem] flex flex-col">

                    <RouterLink to="/new/books" class="grid grid-cols-7 px-4 pb-2.5 pt-4 gap-2 focus:outline-none hover:bg-wallpaper/75 transition-colors duration-150 group/quick-item">

                        <div class="grid place-items-center col-span-1 min-w-[1.75rem] w-9 h-9 rounded-full bg-green-100 group-hover/quick-item:bg-brand-secondary transition-colors duration-150">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="transition-colors duration-150 group-hover/quick-item:stroke-brand-primary stroke-green-600 w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                            </svg>

                        </div>

                        <div class="col-span-6 flex flex-col gap-1">
                            <p class="font-medium group-hover/quick-item:text-brand-primary transition-colors duration-150">New Book</p>
                            <p class="text-subtitle">
                                Register a new book into inventory.
                                Go to "New Transaction" instead to add new book with first purchase
                            </p>
                        </div>

                    </RouterLink>

                    <RouterLink to="/new/transactions" class="quick-item grid grid-cols-7 px-4 pt-2.5 gap-2 focus:outline-none hover:bg-wallpaper/75 transition-colors duration-150 group/quick-item">

                        <div class="grid place-items-center col-span-1 min-w-[1.75rem] w-9 h-9 rounded-full bg-yellow-100 group-hover/quick-item:bg-brand-secondary transition-colors duration-150">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="transition-colors duration-150 group-hover/quick-item:stroke-brand-primary stroke-yellow-600 w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                            </svg>

                        </div>

                        <div class="col-span-6 flex flex-col gap-1">
                            <p class="font-medium group-hover/quick-item:text-brand-primary transition-colors duration-150">New Transaction</p>
                            <p class="text-subtitle">
                                Make a purchase or sale transaction of a new or existing item. Sale can only be made
                                from a primary store
                            </p>
                        </div>

                    </RouterLink>

                </ul>

            </div>

        </div>

        <!-- Quick Navigation -->
        <div v-if="authenticated" class="cursor-pointer sm:hidden relative w-12 h-12 rounded-full flex items-center justify-center group">

            <!--            <ArrowTopRightOnSquareIcon @click="toggleQuickNavigationsView" :class="[viewQuickNavigationsMenu && !! book ? 'scale-110 stroke-black stroke-2' : 'scale-100 stroke-subtitle stroke-[1.75]', !! book ? 'group-hover:scale-110 group-hover:stroke-black group-hover:stroke-2' : '']" class="w-6 h-6 transition-transform duration-300" />-->
            <div>
                <RocketLaunchIcon v-if="! viewQuickNavigationsMenu" @click="toggleQuickNavigationsView" :class="[!! book ? 'hover:scale-110 hover:translate-x-0.5 hover:-translate-y-0.5 hover:stroke-2' : 'opacity-50']" class="w-6 h-6 stroke-subtitle transition-transform duration-150" />
                <RocketLaunchIconSolid v-else class="w-6 h-6 fill-subtitle" />
            </div>

            <div @click="toggleQuickNavigationsView" v-if="viewQuickNavigationsMenu" class="flex flex-col overflow-hidden absolute right-0 -mr-3 -mb-3 z-50 bottom-0 translate-y-full animate-appear-down bg-white border border-border-light shadow-sm rounded-[0.75rem]">

                <div ref="quickNavigationsMenu" class="grid grid-cols-2 min-w-[32rem] gap-1.5 p-[0.5rem]">

                    <RouterLink :to="'/books/' + book.id" class="quick-link col-span-1 row-span-1 group hover:bg-border-dark/50 bg-border-dark/25 transition-colors duration-150 min-h-[6rem] flex flex-col justify-around px-4 py-3 rounded-tl-[0.25rem] w-full">

                        <BookOpenIcon class="w-6 h-6" />

                        <label class="font-medium flex items-center gap-2">
                            Go to Book Details
                            <ArrowRightIcon class="w-4 h-4 -translate-x-full opacity-0 quick-link-arrow duration-150" />
                        </label>

                    </RouterLink>

                    <component
                        @click="(event) => { event.preventDefault() }"
                        :is="book.type === 'consignment' ? 'router-link' : 'span'"
                        :to="'/consignments/' + book.id"
                        class="quick-link col-span-1 row-span-1 group transition-colors duration-150 min-h-[6rem] flex flex-col justify-around px-4 py-3 rounded-tr-[0.25rem] w-full"
                        :class="[book.type === 'consignment' ? 'bg-border-dark/25 hover:bg-border-dark/50' : 'bg-white border opacity-50']"
                    >

                        <CogIcon class="w-6 h-6" />

                        <label class="font-medium flex items-center gap-2">
                            Go to Consignment Details
                            <ArrowRightIcon v-if="book.type === 'consignment'" class="w-4 h-4 -translate-x-full opacity-0 quick-link-arrow duration-150" />
                        </label>

                    </component>

                    <RouterLink :to="'/new/transactions/' + book.id" class="quick-link col-span-1 row-span-1 group hover:bg-border-dark/50 bg-border-dark/25 transition-colors duration-150 min-h-[6rem] flex flex-col justify-around px-4 py-3 rounded-bl-[0.25rem] w-full">

                        <ArrowPathRoundedSquareIcon class="w-6 h-6" />

                        <label class="font-medium flex items-center gap-2">
                            Make New Transaction
                            <ArrowRightIcon class="w-4 h-4 -translate-x-full opacity-0 quick-link-arrow duration-150" />
                        </label>

                    </RouterLink>

                    <RouterLink :to="'/update/books/' + book.id" class="quick-link col-span-1 row-span-1 group hover:bg-border-dark/50 bg-border-dark/25 transition-colors duration-150 min-h-[6rem] flex flex-col justify-around px-4 py-3 rounded-br-[0.25rem] w-full">

                        <ArrowPathIcon class="w-6 h-6" />

                        <label class="font-medium flex items-center gap-2">
                            Update Book Details
                            <ArrowRightIcon class="w-4 h-4 -translate-x-full opacity-0 quick-link-arrow duration-150" />
                        </label>

                    </RouterLink>

                </div>

            </div>

        </div>

        <!-- Notifications -->
        <div v-if="authenticated" class="cursor-pointer sm:hidden relative w-12 h-12 rounded-full flex items-center justify-center">

            <div>
                <InboxStackIcon v-if="! viewNotificationsMenu" @click="toggleNotificationsView" class="hover:stroke-2 w-6 h-6 stroke-subtitle transition-transform duration-150" />
                <InboxStackIconSolid v-else class="w-7 h-7 fill-subtitle" />
            </div>

            <div v-if="(! loadingNotifications) && (unreadNotifications.length > 0)" class="top-2 right-2 text-white rounded-full flex items-center justify-center absolute w-4 h-4 bg-brand-primary font-medium text-[0.5rem]">
                {{ unreadNotifications.length }}
            </div>

            <div v-if="viewNotificationsMenu" class="flex flex-col overflow-hidden absolute right-0 -mr-3 -mb-3 z-50 bottom-0 translate-y-full animate-appear-down bg-white border border-border-light shadow-sm rounded-md">

                <ul ref="notificationMenu" class="scrollbar-mobile w-96 max-h-[350px] overflow-overlay min-h-[3rem] flex flex-col">

                    <li v-if="loadingNotifications" class="flex flex-col gap-1 items-center justify-center text-subtitle p-4 w-full border-b ">

                        <svg class="animate-rotate" width="19" height="20" viewBox="0 0 19 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.4706 5.94533C18.1987 5.55015 18.4769 4.63079 17.9774 3.96992C17.0157 2.69771 15.7589 1.66786 14.3065 0.974827C12.4006 0.0653761 10.2557 -0.217352 8.1792 0.167163C6.10271 0.551679 4.20125 1.5837 2.74734 3.11531C1.29344 4.64692 0.361732 6.59949 0.0857576 8.69317C-0.190217 10.7869 0.203708 12.9142 1.21108 14.7702C2.21844 16.6262 3.78753 18.1157 5.69346 19.0252C7.59939 19.9346 9.74431 20.2174 11.8208 19.8328C13.4032 19.5398 14.8839 18.8708 16.1424 17.8912C16.7961 17.3824 16.7656 16.4223 16.1648 15.852V15.852C15.5639 15.2816 14.621 15.3229 13.9359 15.7887C13.1386 16.3308 12.2332 16.7055 11.2746 16.883C9.82102 17.1521 8.31958 16.9542 6.98542 16.3176C5.65127 15.681 4.55291 14.6384 3.84775 13.3391C3.1426 12.0399 2.86685 10.5508 3.06003 9.08522C3.25321 7.61964 3.90541 6.25284 4.92314 5.18072C5.94087 4.10859 7.27189 3.38618 8.72544 3.11701C10.179 2.84785 11.6804 3.04576 13.0146 3.68238C13.8945 4.10224 14.6718 4.69872 15.3015 5.42894C15.8424 6.05634 16.7425 6.34051 17.4706 5.94533V5.94533Z" fill="#FF8100"/>
                        </svg>

                    </li>

                    <template v-else>

                        <li @click="notificationAction(unread.type, unread.id, unread.data)" v-for="unread in unreadNotifications" class="hover:bg-border-light/25 cursor-pointer flex gap-5 p-4 w-full border-b grid grid-cols-12">

                            <div class="col-span-2 w-12 flex justify-center items-start">
                                <div class="grid place-items-center w-9 h-9 rounded-full" :class="[ unread.data.status === 'error' && unread.type === 'App\\Notifications\\QueueJobFinished' ? 'bg-red-100' : 'bg-green-100' ]">
                                    <div v-if="unread.type === 'App\\Notifications\\NewSubscriber'" class="text-xl text-green-600 w-8 h-8 rounded-full bg-green-200 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                                        </svg>
                                    </div>
                                    <div v-if="unread.type === 'App\\Notifications\\QueueJobFinished'" :class="[ unread.data.status === 'success' ? 'bg-green-200' : 'bg-red-200' ]" class="text-xl w-8 h-8 rounded-full flex items-center justify-center">
                                        <WrenchIcon class="w-5 h-5 stroke-[1.5]" :class="[ unread.data.status === 'success' ? 'stroke-green-600' : 'stroke-red-600']" />
                                    </div>
                                    <div v-if="unread.type === 'App\\Notifications\\NewReminder'" class="bg-green-200 text-xl w-8 h-8 rounded-full flex items-center justify-center">
                                        <BellAlertIcon class="w-5 h-5 stroke-[1.5] stroke-green-600" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-10">
                                <div v-if="unread.type === 'App\\Notifications\\NewSubscriber'" class="grow flex flex-col gap-1">
                                    <div class="flex items-center justify-between">
                                        <p class="font-medium">New Subscriber</p>
                                        <h5 class="text-subtitle text-xs">{{ format(unread.created_at, 'my-locale') }}</h5>
                                    </div>
                                    <p class="text-subtitle">
                                        <span class="text-black">{{ unread.data.name }}</span> subscribed to your channel.
                                        You can find them in the subscribers list.
                                    </p>
                                </div>
                                <div v-else-if="unread.type === 'App\\Notifications\\QueueJobFinished'" class="grow flex flex-col gap-1">
                                    <div class="flex items-center justify-between">
                                        <p class="font-medium">Queued Job {{ unread.data.status === 'success' ? 'Finished' : 'Failed' }}</p>
                                        <h5 class="text-subtitle text-xs">{{ format(unread.created_at, 'my-locale') }}</h5>
                                    </div>
                                    <div v-if="unread.data.status === 'success'">
                                        <p class="text-subtitle">
                                            {{ unread.data.description }}.
                                            Time elapsed: {{ (unread.data.time / 10000).toFixed(2) }} sec
                                        </p>
                                    </div>
                                    <div v-else class="flex flex-col gap-1">
                                        <p class="text-subtitle">
                                            {{ unread.data.description }}
                                            Time elapsed: {{ (unread.data.time / 10000).toFixed(2) }} sec
                                        </p>
                                        <p class="text-xs text-red-600">
                                            (Error code {{ unread.data.error.code }})
                                        </p>
                                    </div>
                                </div>
                                <div v-else-if="unread.type === 'App\\Notifications\\NewReminder'" class="grow flex flex-col gap-1">
                                    <div class="flex items-center justify-between">
                                        <p class="font-medium">Reminder Alert</p>
                                        <h5 class="text-subtitle text-xs">{{ format(unread.created_at, 'my-locale') }}</h5>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <p class="text-subtitle">
                                            {{ unread.data.description }}.
                                        </p>
                                        <p class="text-subtitle text-xs">
                                            Priority
                                            <span
                                                class="font-medium"
                                                :class="{
                                                'text-red-600': unread.data.priority === 'High',
                                                'text-yellow-600': unread.data.priority === 'Medium',
                                                'text-gray-600': unread.data.priority === 'Low',
                                            }"
                                            >
                                            {{ unread.data.priority }}
                                        </span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </li>

                        <li v-if="unreadNotifications.length === 0" class="flex flex-col gap-1 items-center justify-center text-subtitle p-4 w-full border-b ">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="stroke-brand-primary scale-75 w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.143 17.082a24.248 24.248 0 003.844.148m-3.844-.148a23.856 23.856 0 01-5.455-1.31 8.964 8.964 0 002.3-5.542m3.155 6.852a3 3 0 005.667 1.97m1.965-2.277L21 21m-4.225-4.225a23.81 23.81 0 003.536-1.003A8.967 8.967 0 0118 9.75V9A6 6 0 006.53 6.53m10.245 10.245L6.53 6.53M3 3l3.53 3.53" />
                            </svg>
                            <p class="font-medium text-black">No unread notifications</p>
                            <p class="text-center">You don't have any unread notifications. You can view all your notifications instead.</p>
                            <button class="px-2 py-1 rounded-md bg-brand-secondary scale-90 mt-2 text focus:outline-none text-brand-primary">View all</button>

                        </li>

                    </template>

                </ul>

                <div v-if="unreadNotifications.length > 0" class="shadow-md border-t bg-white cursor-pointer flex items-center justify-between gap-5 p-4 w-full ">

                    <button @click="clearAll" class="text-brand-primary focus:outline-none">Clear all</button>
                    <button class="text-brand-primary focus:outline-none">See all notifications</button>

                </div>

            </div>

        </div>

        <!-- Profile Menu -->
        <div v-if="authenticated" class="cursor-pointer flex items-center w-12 h-12 justify-center gap-6">
            <div @click="toggleView" class="z-10 cursor-pointer relative font-medium rounded-full shadow-sm flex items-center justify-center">

                <div>
                    <UserCircleIcon v-if="! viewHeaderMenu" class="hover:scale-110 hover:stroke-2 w-6 h-6 stroke-subtitle transition-transform duration-150" />
                    <UserCircleIconSolid v-else class="w-7 h-7 fill-subtitle" />
                </div>

                <ul ref="headerMenu" :class="[viewHeaderMenu ? '' : 'hidden']" class="divide-y-[1.5px] divide-border-dark flex flex-col animate-appear-down text-subtitle font-medium right-0 -mr-3 -mb-6 z-50 bottom-0 translate-y-full overflow-auto absolute max-h-72 min-w-[14rem] bg-white shadow-md rounded-md border-[0.5px] border-border-light">
                    <li class="grid place-items-center h-10 whitespace-nowrap px-4 bg-wallpaper">
                        <span class="truncate flex items-center justify-center gap-1 w-full">
                            Hello,
                            <span class="text-black">{{ user.name.split(' ')[0] }}!</span>
                        </span>
                    </li>
                    <li @click="settings" class="hover:bg-wallpaper hover:text-brand flex justify-start gap-4 items-center h-10 px-4 group cursor-pointer">
                        <WrenchScrewdriverIcon class="w-4 h-4 stroke-subtitle" />
                        <p class="capitalize">Settings</p>
                    </li>
                    <li @click="attemptLogout" class="hover:bg-red-50 text-red-600 hover:text-brand flex justify-start gap-5 items-center h-10 px-4 group cursor-pointer">
                        <XCircleIcon class="w-4 h-4 stroke-red-600 stroke-2" />
                        <p class="capitalize">Log out</p>
                    </li>
                </ul>

            </div>
        </div>

        <!-- Unauthenticated -->
        <button v-if="!authenticated" class="text-subtitle px-4 py-2 rounded-md hover:bg-wallpaper capitalize">request new password</button>

    </header>
    <div :class="[inputFocused ? 'w-full' : 'w-0']" class="transition-width duration-150 border-b border-brand-primary"></div>
  </div>

    <InfoModalCard :open="viewErrorNotificationInfoCard && !! errorData?.error" @close="viewErrorNotificationInfoCard =! viewErrorNotificationInfoCard" :title="errorData?.description">

        <template #description>
            {{ errorData.error.message }}
        </template>

    </InfoModalCard>

</template>

<script setup>

    import { ref, computed, onMounted, watch } from "vue"
    import { onClickOutside } from "@vueuse/core"
    import store from "../store"
    import router from "../router"
    import {
        UserCircleIcon, BellIcon, BellAlertIcon, InboxStackIcon, RocketLaunchIcon,
        EllipsisHorizontalCircleIcon, BookOpenIcon, ArrowRightIcon, CogIcon,
        ArrowPathRoundedSquareIcon, ArrowPathIcon, WrenchScrewdriverIcon, XCircleIcon, WrenchIcon
    } from "@heroicons/vue/24/outline"
    import {
        UserCircleIcon as UserCircleIconSolid, BellIcon as BellIconSolid,
        BellAlertIcon as BellAlertIconSolid, InboxStackIcon as InboxStackIconSolid,
        RocketLaunchIcon as RocketLaunchIconSolid, EllipsisHorizontalCircleIcon as EllipsisHorizontalCircleIconSolid
    } from "@heroicons/vue/24/solid"
    import axios from "axios";
    import { local_time_ago } from "../utils";
    import { format, register } from 'timeago.js';
    import InfoModalCard from "./InfoModalCard.vue";

    register('my-locale', local_time_ago)

    const authenticated = computed(() => store.state.auth.isAuthenticated)
    const user = computed(() => store.state.auth.user)
    const book = computed(() => store.state.book.book)

    const viewNotificationsMenu = ref(false)
    const viewQuickActionsMenu = ref(false)
    const viewQuickNavigationsMenu = ref(false)
    const viewHeaderMenu = ref(false)
    const viewRemindersForm = ref(false)
    const headerMenu = ref(null)
    const notificationMenu = ref(null)
    const quickActionsMenu = ref(null)
    const quickNavigationsMenu = ref(null)
    const remindersForm = ref(null)

    function toggleNotificationsView() {
        viewNotificationsMenu.value = !viewNotificationsMenu.value
    }

    function toggleQuickActionsView() {
        viewQuickActionsMenu.value = !viewQuickActionsMenu.value
    }

    function toggleRemindersFormView() {
        viewRemindersForm.value = !viewRemindersForm.value
    }

    function toggleQuickNavigationsView() {
        if (!! book.value) {
            viewQuickNavigationsMenu.value = !viewQuickNavigationsMenu.value
        } else {
            viewQuickNavigationsMenu.value = false
        }
    }

    function toggleView() {
        viewHeaderMenu.value = !viewHeaderMenu.value
    }

    onClickOutside(headerMenu, () => {

        if (viewHeaderMenu.value) {
            toggleView()
        }

    })

    onClickOutside(notificationMenu, () => {
        if (viewNotificationsMenu.value) {
            toggleNotificationsView()
        }
    })

    onClickOutside(quickActionsMenu, () => {
        if (viewQuickActionsMenu.value) {
            toggleQuickActionsView()
        }
    })

    onClickOutside(quickNavigationsMenu, () => {
        if (viewQuickNavigationsMenu.value) {
            toggleQuickNavigationsView()
        }
    })

    onClickOutside(remindersForm, () => {
        if (viewRemindersForm.value) {
            toggleRemindersFormView()
        }
    })

    function settings() {

    }


    function toggleNavigation() {

        store.dispatch('toggleNavigation')

    }

    const inputFocused = ref(false)
    function toggleInputFocus(state) {
        inputFocused.value = state
    }

    function attemptLogout() {
        store.dispatch("logout")
            .then(() => {
                router.push({ name: "Login" })
            })
    }

    const unreadNotifications = ref([])
    const loadingNotifications = ref(true)
    async function getUnreadNotifications() {

        await axios.get('/api/notifications/unread')
            .then(response => {
                console.log(response.data.data)
                unreadNotifications.value = response.data.data
            })
            .catch(error => {
                alert(error)
            })

    }

    async function clearAll() {

        await axios.post('/api/notifications/clear-all')
            .then(() => getUnreadNotifications())
            .catch()
    }

    function notificationAction(type, id, data) {

        switch (type) {

            case 'App\\Notifications\\NewSubscriber':

                store.dispatch('toggleContentLoading', true)

                axios.post('/api/notifications/read', {
                    id
                }).then(() => {
                    getUnreadNotifications().then(() => {
                        router.push({ name: 'MailingLists' })
                        store.dispatch('toggleContentLoading', false)
                    })
                })
                break;

            case `App\\Notifications\\QueueJobFinished`:

                if (data.status === 'error') {

                    viewErrorNotificationInfoCard.value = true
                    errorData.value = data

                }

                axios.post('/api/notifications/read', {
                    id
                })

                break;

            default:
                break;

        }

        toggleNotificationsView()

    }

    const searchInput = ref(null)

    watch(authenticated, () => {

        if (authenticated.value) {
            getUnreadNotifications()
                .then()
                .finally(() => {
                    loadingNotifications.value = false
                })
        }

    })

    const viewErrorNotificationInfoCard = ref(false)
    const errorData = ref()

    onMounted(() => {

        if (authenticated.value) {
            getUnreadNotifications()
                .then()
                .finally(() => {
                    loadingNotifications.value = false
                })
        }

        document.onkeydown = function(e) {
            if (e.ctrlKey && e.keyCode === 75) {
                searchInput.value.focus()
                return false;
            }
        }

    })

    Echo.private('mailing-list')
        .listen('SubscriberRegistered', (e) => {

            console.log('NewSubscriberRegistered event successfully fired ', e)

            getUnreadNotifications()
                .then()
                .finally(() => {
                    loadingNotifications.value = false

                    store.dispatch('pushAlert', {
                        type: 'default',
                        message: `${e['mailingList'].name} just subscribed to your mailing list!`,
                    })

                })



        })

</script>

<style scoped>

    .overflow-overlay {
        overflow: overlay;
    }

    .scrollbar-mobile::-webkit-scrollbar {
        @apply w-1.5;
    }

    .scrollbar-mobile::-webkit-scrollbar-track {
        @apply bg-transparent;
    }

    .scrollbar-mobile::-webkit-scrollbar-thumb {
        @apply bg-border-light;
    }

    .scrollbar-mobile::-webkit-scrollbar-thumb:hover {
        @apply bg-border-dark;
    }

    .list .quick-item:last-child {
        @apply pb-4;
    }

    .quick-link:hover .quick-link-arrow {

        @apply translate-x-0 opacity-100;

    }

</style>
