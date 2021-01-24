<template>
  <div class="mt-4 container">
    <div class="text-center" v-if="isLoading">
        <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <div v-else-if="ShowData">
        <div class="row">
            <div class="col-lg-5" >
                <img class="show-image" v-if="ShowData.image" width="100%" height="auto"  :src="ShowData.image.original" :alt="ShowData.name">
            </div>
            <div class="col-lg-5">
                <h1> {{ ShowData.name }}</h1>
                <hr>
                <h5> Summary</h5>
                <p>
                    <div v-html="ShowData.summary"></div>
                </p>
                <hr>

                <div class="row row-cols-auto">
                    <div class="col-3"><strong>Premiered</strong></div>
                    <div class="col">{{ShowData.premiered}}</div>
                </div>

                <div class="row row-cols-auto">
                    <div class="col-3"><strong>Language</strong></div>
                    <div class="col">{{ShowData.language}}</div>
                </div>

                <div class="row row-cols-auto">
                    <div class="col-3"><strong> Genres</strong></div>
                    <div class="col"> <span class="mr-3" v-for="genre in ShowData.genres" :key="genre">{{ genre }}</span> </div>
                </div>

                <div class="row row-cols-auto" v-if="ShowData.network">
                    <div class="col-3"><strong>Network</strong></div>
                    <div class="col">{{ShowData.network.name}} </div>
                </div>
                <hr>

            </div>

        </div>

    </div>



  </div>
</template>

<script>
export default {
    data () {
        return {
            isLoading : false,

            ShowData : []
        }
    },
    methods: {

        getData(){
            this.isLoading = true

            const url = `/api/shows/${this.$route.params.showid}`;

            axios.get(url)
            .then(result=> this.ShowData = result.data)
            .catch(error=> this.error = error)
            .finally(() => this.isLoading = false);
        }
    },
    mounted()  {
        this.getData()
    }
}

</script>
<style>
.show-image{
    overflow: hidden;
    box-shadow: 0 0 5px rgba(10, 10, 10, 0.3);
    margin-bottom:25px;
    border-radius: 10px;
}

</style>
