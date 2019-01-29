<template>
    <div :id="'reply-'+ reply.id" class="panel" :class="isBest ? 'panel-success' : 'panel-default'">
        <div class="panel-heading">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/' + reply.owner.name" v-text="reply.owner.name"></a>
                    回复于 {{ reply.created_at }}
                </h5>

                <div v-if="signedIn">
                    <favorite :reply="reply"></favorite>
                </div>

            </div>
        </div>

        <div class="panel-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea title="edit" class="form-control" v-model="body" required></textarea>
                </div>
                <div class="text-right">
                    <button class="btn btn-primary btn-xs" @click="update">提交</button>
                    <button class="btn btn-link btn-xs" @click="cancelReply">取消</button>
                </div>
            </div>

            <div v-else v-html="body"></div>

        </div>

        <div  class="panel-footer level" v-if="authorize('owns',reply) || authorize('owns',reply.thread)">

            <div v-if="authorize('owns',reply)">
                <button class="btn btn-xs mr-1" @click="editReply">编辑</button>
                <button class="btn btn-danger btn-xs mr-1" @click="destroy">删除</button>
            </div>

            <button class="btn btn-xs btn-default ml-a"
                    @click="markBestReply"
                    v-show="! isBest"
                    v-if="authorize('owns',reply.thread)"
            >最佳回复</button>

        </div>

    </div>
</template>

<script>
    import Favorite from './Favorite';

    export default {
        name: "reply",
        props: ['reply'],

        components: { Favorite },

        data() {
            return {
                editing: false,
                id: this.reply.id,
                body: this.reply.body,
                isBest: this.reply.isBest
            };
        },

        created() {
            window.events.$on('best-reply-selected',id => {
                this.isBest = (id === this.id)
            });
        },

        methods: {
            update() {
                let url = '/replies/' + this.reply.id;
                axios.patch(url, {
                    body:this.body
                })
                    .catch(error => {
                        flash(':( ' + error.response.data, 'danger');
                    });

                this.editing = false;

                flash('已更新');
            },

            destroy() {
                axios.delete('/replies/' + this.reply.id);
                this.$emit('deleted', this.reply.id);
            },

            editReply() {
                this.old_body_data = this.body;
                this.editing = true;
            },

            cancelReply() {
                this.body = this.old_body_data;
                this.old_body_data = '';
                this.editing = false;
            },

            markBestReply() {
                this.isBest = true;

                axios.post('/replies/' + this.reply.id + '/best');

                window.events.$emit('best-reply-selected',this.reply.id);
            }
        }
    }
</script>

<style scoped>

</style>