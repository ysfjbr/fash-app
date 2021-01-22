<template>
  <div class="mt-4 container">
      <div >
          <input class="form-control mr-sm-2" type="search" v-model="search_text" placeholder="Search" aria-label="Search" @keyup="submit">
      </div>
      <div v-if="isLoading">
          loading ...
      </div>

      <div v-else>
            <router-link class="nav-link" :to="`/show/${ show.id }`" v-for="show in showsToShow" :key="show.id">{{ show.name }}</router-link>
      </div>
  </div>
</template>

<script>
export default {
    data () {
        return {
            isLoading: false,
            search_text:"",
            allShows:{},
            showsToShow:[],
            showedAmount: 1,
            amounts: [10 , 25, 50]
        }
    },
    methods: {
        submit(e){
            if(this.search_text.length > 3)
                this.search(this.search_text)
            else if(this.search_text.length === 0) this.getAllShow(1)
        },
        search(search){
            this.getData({data:{search}, callback : res => {
                this.showsToShow = res.filter(show => show.score > 8).map(show => show.show)
            }
            })
        },

        getAllShow(page){

            if(this.allShows[page])
            {
                console.log('is loaded');
            }
            else{
                console.log('loading');
                this.getData({data:{page}, callback : res => {
                        this.allShows[page] = res
                    }
                })
            }
        },

        getData(payload)  {

            this.isLoading = true

            let url
            if(payload.objectid)
            url =`/api/${ payload.clname }/${ payload.objectid }`;
            else
            url =`/api/shows/`;
            console.log("payload",url);

            axios.get(url,{params: payload.data})
            .then(res=> payload.callback(res.data))
            .catch(err=> console.log(err))
            .finally(() => this.isLoading = false);
        }
    },
    mounted () {
         //this.getAllShow(1)
    }
}
</script>

<style>

</style>
