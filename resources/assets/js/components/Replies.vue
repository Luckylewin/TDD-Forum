<template>
    <div>
        <div v-for="(reply, index) in items">
            <reply :reply="reply" @deleted="remove(index)" :key="reply.id"></reply>
        </div>

        <paginator :dataSet="dataSet" @changed="fetch"></paginator>

        <p v-if="$parent.locked">
            This thread is locked.No more replies are allowed
        </p>

        <new-reply @created="add" v-else></new-reply>
    </div>
</template>

<script>
    import Reply from './Reply.vue';
    import NewReply from './NewReply.vue';
    import Collection from '../mixins/Collection.vue'

    export default {
        name: 'Replies',

        props: ['data'],

        components: { Reply,NewReply},

        mixins: [Collection],

        data() {
            return {
                dataSet: false
            }
        },

        created() {
            this.fetch()
        },

        methods: {
            url(page) {
               if (!page) {
                   let query = location.search.match(/page=(\d+)/);

                   page = query ? query[1] : 1;
               }

                return `${location.pathname}/replies?page=${page}`
            },

            fetch(page) {
                axios.get(this.url(page))
                     .then(this.refresh);
            },

            refresh({data}) {
                this.dataSet = data;
                this.items = data.data;

                window.scrollTo(0,0);
            }
        }
    }
</script>