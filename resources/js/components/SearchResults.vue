<template>
<div class="mt-3">
   <div class="text-center" v-if="isloading">
        <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
   <div v-else>
        <show-items :showslist="results" :islistview="islistview" />
    </div>
</div>
</template>

<script>
export default {
    props: ["results", "isloading","islistview"]
}
</script>

<style>

</style>
