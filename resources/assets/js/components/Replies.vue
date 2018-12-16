<template>
    <div>
        <div v-for="(reply, index) in items">
            <reply :data="reply" @deleted="remove(index)"></reply>
        </div>

        <new-reply :endpoint="endpoint" @created="add"></new-reply>
    </div>
</template>

<script>
    import Reply from './Reply.vue';
    import NewReply from './NewReply.vue';

    export default {
        name: 'Replies',

        props: ['data'],

        components: { Reply,NewReply},

        data() {
            return {
                items:this.data,
                endpoint: location.pathname+'/replies'
            }
        },

        methods: {
            remove(index) {
                this.items.splice(index, 1);

                this.$emit('removed');

                flash('已删除该回复');
            },

            add(reply) {

                this.$emit('added');

                this.items.push(reply)
            }
        }
    }
</script>