<template>
    <div class="panel" v-if="islistview">
            <table class="table">

            <thead>
            <tr>
                <th scope="col"></th>
                <th scope="col">Show Title</th>
                <th scope="col" class="d-none d-lg-block">Premiered</th>
                <th scope="col" >Genre</th>
                <th scope="col" class="d-none d-lg-block">Language </th>

                </tr>
            </thead>
            <tbody>
                <show-item  v-for="show in showslist" :key="show.id" :showdata="show" :islistview="islistview" />
                </tbody>
            </table>
    </div>
    <div class="row" v-else>
            <show-item  v-for="show in showslist" :key="show.id" :showdata="show" :islistview="islistview" />
    </div>
</template>

<script>
export default {
    props: ["islistview", "showslist"]
}
</script>

<style>
.panel{
    padding:25px;
    background-color:white;
    border:1px solid #ccc;
    border-radius:5px;
}
</style>
