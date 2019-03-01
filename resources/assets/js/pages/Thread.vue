<script>
    import Replies from '../components/Replies.vue';
    import SubscribeButton from '../components/SubscribeButton';

    export default {
        name: 'thread',

        props: ['thread',],

        components: { Replies,SubscribeButton },

        data() {
            return {
                repliesCount: this.thread.replies_count,
                locked:this.thread.locked,
                editing:false,
                title:this.thread.title,
                body:this.thread.body,
                form:{}
            }
        },

        created() {
            this.resetForm();
        },

        methods: {
            toggleLock() {
                let uri = `/locked-threads/${this.thread.slug}`;

                axios[this.locked ? 'delete' : 'post'](uri);

                this.locked = ! this.locked;
            },

            update() {

                let url = `/threads/${this.thread.channel.slug}/${this.thread.slug}`;

                axios.patch(url,{
                    title: this.form.title,
                    body: this.form.body
                }).then((response)=>{

                    this.editing = false;
                    this.title = this.form.title;
                    this.body = this.form.body;

                    flash('The thread has been updated!');
                }).catch((error)=>{
                    if (error.response.status === 422) {
                        flash('invalid title or body','danger');
                    } else {
                        flash('internal server error','danger');
                    }
                });
            },

            resetForm() {

                this.form.title = this.thread.title;
                this.form.body = this.thread.body;

                this.editing = false;
            }
        }
    }
</script>