<template>
  <nav class="navbar sticky-top navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <router-link class="navbar-brand" to="/" >
                    <span @click="scrollToTop"> Home Page </span>
                </router-link>
            </div>
        </nav>
</template>

<script>
export default {
    methods: {
        scrollToTop() {
                window.scrollTo(0,0);
           }
    }
}
</script>

<style>

</style>
