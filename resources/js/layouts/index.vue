<template>

    <div class="flex flex-row w-full relative">

        <Navigation v-if="authenticated">

            <div class="flex flex-col justify-between h-full">

                <div class="flex flex-col">
                    <NavigationLink v-slot="props" label="dashboard" route="">
                        <svg width="18" height="18" viewBox="0 0 18 18" :class="[props.active ? 'fill-white' : 'fill-black']" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 10H7C7.26522 10 7.51957 9.89464 7.70711 9.70711C7.89464 9.51957 8 9.26522 8 9V1C8 0.734784 7.89464 0.48043 7.70711 0.292893C7.51957 0.105357 7.26522 0 7 0H1C0.734784 0 0.48043 0.105357 0.292893 0.292893C0.105357 0.48043 0 0.734784 0 1V9C0 9.26522 0.105357 9.51957 0.292893 9.70711C0.48043 9.89464 0.734784 10 1 10ZM0 17C0 17.2652 0.105357 17.5196 0.292893 17.7071C0.48043 17.8946 0.734784 18 1 18H7C7.26522 18 7.51957 17.8946 7.70711 17.7071C7.89464 17.5196 8 17.2652 8 17V13C8 12.7348 7.89464 12.4804 7.70711 12.2929C7.51957 12.1054 7.26522 12 7 12H1C0.734784 12 0.48043 12.1054 0.292893 12.2929C0.105357 12.4804 0 12.7348 0 13V17ZM10 17C10 17.2652 10.1054 17.5196 10.2929 17.7071C10.4804 17.8946 10.7348 18 11 18H17C17.2652 18 17.5196 17.8946 17.7071 17.7071C17.8946 17.5196 18 17.2652 18 17V10C18 9.73478 17.8946 9.48043 17.7071 9.29289C17.5196 9.10536 17.2652 9 17 9H11C10.7348 9 10.4804 9.10536 10.2929 9.29289C10.1054 9.48043 10 9.73478 10 10V17ZM11 7H17C17.2652 7 17.5196 6.89464 17.7071 6.70711C17.8946 6.51957 18 6.26522 18 6V1C18 0.734784 17.8946 0.48043 17.7071 0.292893C17.5196 0.105357 17.2652 0 17 0H11C10.7348 0 10.4804 0.105357 10.2929 0.292893C10.1054 0.48043 10 0.734784 10 1V6C10 6.26522 10.1054 6.51957 10.2929 6.70711C10.4804 6.89464 10.7348 7 11 7Z" />
                        </svg>
                    </NavigationLink>
                    <NavigationLink v-slot="props" label="books">
                        <svg width="18" height="20" viewBox="0 0 18 20" :class="[props.active ? 'fill-white' : 'fill-black']" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 20H18V18H3.012C2.55 17.988 2 17.805 2 17C2 16.195 2.55 16.012 3.012 16H18V2C18 0.897 17.103 0 16 0H3C1.794 0 0 0.799 0 3V17C0 19.201 1.794 20 3 20ZM2 6V3C2 2.195 2.55 2.012 3 2H16V14H2V6Z" />
                            <path d="M5 4H14V6H5V4Z"/>
                        </svg>
                    </NavigationLink>
                    <NavigationLink v-slot="props" label="transactions">
                        <svg width="20" height="16" viewBox="0 0 20 16" :class="[props.active ? 'fill-white' : 'fill-black']" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13 8L18 4L13 0V2.999H0V4.999H13V8ZM20 11H7V8L2 12L7 16V13H20V11Z"/>
                        </svg>
                    </NavigationLink>
                    <NavigationLink v-slot="props" label="stock records" route="stocks">
                        <svg width="18" height="18" viewBox="0 0 18 18" :class="[props.active ? 'fill-white' : 'fill-black']" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 2C18 0.897 17.103 0 16 0H2C0.897 0 0 0.897 0 2V16C0 17.103 0.897 18 2 18H16C17.103 18 18 17.103 18 16V2ZM2 16V2H16L16.002 16H2Z" />
                            <path d="M4 4H5.998V6H4V4ZM8 4H14V6H8V4ZM4 8H5.998V10H4V8ZM8 8H14V10H8V8ZM4 12H5.998V14H4V12ZM8 12H14V14H8V12Z" />
                        </svg>
                    </NavigationLink>
                    <NavigationLink v-slot="props" label="daily sales" route="daily-sales">
                        <svg width="18" height="20" viewBox="0 0 18 20" :class="[props.active ? 'fill-white' : 'fill-black']" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 18V4C18 2.897 17.103 2 16 2H14V0H12V2H6V0H4V2H2C0.897 2 0 2.897 0 4V18C0 19.103 0.897 20 2 20H16C17.103 20 18 19.103 18 18ZM6 16H4V14H6V16ZM6 12H4V10H6V12ZM10 16H8V14H10V16ZM10 12H8V10H10V12ZM14 16H12V14H14V16ZM14 12H12V10H14V12ZM16 7H2V5H16V7Z" />
                        </svg>
                    </NavigationLink>
                    <NavigationLink v-slot="props" label="suppliers">
                        <svg width="19" height="20" viewBox="0 0 19 20" :class="[props.active ? 'fill-white' : 'fill-black']" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16.5 1C15.8371 1.00053 15.2015 1.26409 14.7328 1.73282C14.2641 2.20155 14.0005 2.83712 14 3.5C14 3.857 14.078 4.196 14.214 4.505L12.259 6.704C11.5949 6.24558 10.807 6.00004 10 6C9.26 6 8.576 6.216 7.981 6.566L5.707 4.293L5.684 4.316C5.88 3.918 6 3.475 6 3C6 2.40666 5.82405 1.82664 5.49441 1.33329C5.16477 0.839943 4.69623 0.455426 4.14805 0.228363C3.59987 0.00129986 2.99667 -0.0581102 2.41473 0.0576455C1.83279 0.173401 1.29824 0.459124 0.878681 0.878681C0.459124 1.29824 0.173401 1.83279 0.0576455 2.41473C-0.0581102 2.99667 0.00129986 3.59987 0.228363 4.14805C0.455426 4.69623 0.839943 5.16477 1.33329 5.49441C1.82664 5.82405 2.40666 6 3 6C3.475 6 3.917 5.88 4.316 5.684L4.293 5.707L6.567 7.98C6.19964 8.59003 6.00375 9.28791 6 10C6 10.997 6.38 11.899 6.985 12.601L4.408 15.177C4.11917 15.0614 3.81111 15.0013 3.5 15C2.122 15 1 16.121 1 17.5C1 18.879 2.122 20 3.5 20C4.878 20 6 18.879 6 17.5C6 17.179 5.934 16.874 5.823 16.591L8.661 13.753C9.082 13.903 9.528 14 10 14C12.206 14 14 12.206 14 10C14 9.364 13.837 8.771 13.572 8.236L15.689 5.853C15.945 5.941 16.215 6 16.5 6C17.879 6 19 4.879 19 3.5C19 2.121 17.879 1 16.5 1ZM10 12C8.897 12 8 11.103 8 10C8 8.897 8.897 8 10 8C11.103 8 12 8.897 12 10C12 11.103 11.103 12 10 12Z" />
                        </svg>
                    </NavigationLink>
                </div>

                <div class="flex flex-col">
                    <h1 class="text-xs text-subtitle text-center pt-8">&copy; አመለወርቅ. 2022</h1>
                </div>

            </div>

        </Navigation>

        <div class="h-screen w-full flex flex-col">

            <Header />

            <div class="grow overflow-auto w-full relative flex flex-col">

                <slot />

                <p class="text-xs text-subtitle text-center pt-8 pb-1">&nbsp;</p>

            </div>

        </div>

    </div>

</template>

<script setup>

    import { computed } from "vue"

    import Navigation from "../components/Navigation.vue"
    import Header from "../components/Header.vue"
    import NavigationLink from "../components/NavigationLink.vue"
    import ContentPage from "./content-page.vue"
    import store from "../store"

    const authenticated = computed(() => store.state.auth.isAuthenticated)

</script>

<style scoped></style>
