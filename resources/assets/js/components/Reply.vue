<template>
    <div :id="'reply-'+ data.id" class="panel panel-default">
        <div class="panel-heading">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/' + data.owner.name" v-text="data.owner.name"></a>
                    回复于 {{ data.created_at }}
                </h5>

                <div v-if="signIn">
                    <favorite :reply="data"></favorite>
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

        <div v-if="canUpdate" class="panel-footer level">
            <button class="btn btn-xs mr-1" @click="EditReply">编辑</button>
            <button class="btn btn-danger btn-xs mr-1" @click="destroy">删除</button>
        </div>

    </div>
</template>

<script>
    import Favorite from './Favorite';

    export default {
        name: "reply",
        props: ['data'],

        components: { Favorite },

        data() {
            return {
                editing: false,
                id: this.data.id,
                body: this.data.body
            };
        },

        computed: {
            signIn() {
                return window.App.signIn;
            },

            canUpdate() {
                return this.authorize(user => this.data.user_id === user.id);
            }
        },

        methods: {
            update() {
                let url = '/replies/' + this.data.id;
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
                axios.delete('/replies/' + this.data.id);
                this.$emit('deleted', this.data.id);
            },

            editReply() {
                this.old_body_data = this.body;
                this.editing = true;
            },

            cancelReply() {
                this.body = this.old_body_data;
                this.old_body_data = '';
                this.editing = false;
            }
        }
    }
</script>

<style scoped>

</style>