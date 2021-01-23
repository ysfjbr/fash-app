<template>
  <div class="mt-4 container">
      <div class="row">
            <div class="col-md-9">
                <input class="form-control mr-sm-2" type="search" v-model="searchText" placeholder="Search" aria-label="Search" @keyup="submit" @change="submit">
            </div>

            <div class="col-md-3">

                <div class="btn-group m-3" role="group" aria-label="First group">
                    View:
                    <div class="form-check form-check-inline ml-3">
                        <input class="form-check-input" type="radio" name="inlineRadioItemsView" id="inlineRadioGrid" :value="false" v-model="isListView"  >
                        <label class="form-check-label" for="inlineRadioGrid">Grid</label>
                    </div>

                    <div class="form-check form-check-inline ml-3">
                        <input class="form-check-input" type="radio" name="inlineRadioItemsView" id="inlineRadioList" :value="true" v-model="isListView"  >
                        <label class="form-check-label" for="inlineRadioList">List</label>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="error">
            <div class="alert alert-danger text-center mt-4"  role="alert">
                {{error}}
            </div>
        </div>

        <div v-if="isSearching">
            <search-results :isloading="isLoading" :results="searchResult" :islistview="isListView"/>
        </div>
        <div v-else>
            <div ref="showsDiv">
                <show-items :showslist="showsList" :islistview="isListView" />

                <div class="text-center" v-if="isLoading">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>

        </div>
  </div>
</template>

<script>

import SearchResults from '../components/SearchResults.vue'

export default {
components: {
    "search-results": SearchResults
},

    data () {
        return {
            isLoading: false,
            isSearching: false,

            searchText:"",
            searchResult: [],

            showsList:[],    // list of shows to display
            currPage: 0,     // Current Page Number

            isListView: false,

            polling: null,
            error: ""
        }
    },
    methods: {
        submit(e){
            // when user typing

            //reset polling = reset time after stop typing...
            clearInterval(this.polling)
            this.searchResult = []
            //is Loading
            this.isLoading = true

            // clear Error
            this.error = "";

            if(this.searchText.length > 0)
            {
                /**
                when user stopped typing for 0.5 second start search (for Optimizng API fetching)
                 */
                this.polling = setInterval(() => {
			        this.search(this.searchText)
                    clearInterval(this.polling)
		        }, 500)

                this.isSearching = true  // to show search results
            }

            // if search input is empty, hide search results
            else {
                this.isSearching = false
            }
        },
        search(search){
            this.getData({data:{search}, callback : res => {
                    this.searchResult = res && res.filter(show => show.score > 5).map(show => show.show)  // score to filter result to get only very closed to input
                }
            })
        },

        getData(payload)  {

            this.isLoading = true

            let url
            if(payload.objectid)
            url =`/api/${ payload.clname }/${ payload.objectid }`;
            else
            url =`/api/shows/`;
            //console.log("payload",url);

            axios.get(url,{params: payload.data})
            .then(result=> payload.callback(result.data))
            .catch(error=> this.error = error)
            .finally(() => this.isLoading = false);
        },
        loadMoreShows(){

                this.getData({data:{page : this.currPage++ }, callback : res => {
                        this.showsList = this.showsList.concat(res)
                    }
                })
        },
        handleScroll:function(e) {
            /**
            check if user scroll to end of page
            */

            if(this.$refs.showsDiv && window.innerHeight +1 > this.$refs.showsDiv.getBoundingClientRect().bottom) this.loadMoreShows();
          },
    },
    mounted () {
        this.loadMoreShows()
    },
    created:function() {
        window.addEventListener('scroll', this.handleScroll);
    },
    destroyed() {
        window.removeEventListener('scroll', this.handleScroll);
    },
    watch: {
        showedAmount: function (va) {
            this.showsList = []
            this.loadMoreShows()
        }
    }
}
</script>

<style>

</style>
