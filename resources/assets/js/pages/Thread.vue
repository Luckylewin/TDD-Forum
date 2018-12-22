<script>
    import Replies from '../components/Replies.vue';
    import SubscribeButton from '../components/SubscribeButton';

    export default {
        name: 'thread',

        props: ['initialRepliesCount'],

        components: { Replies,SubscribeButton },

        data() {
            return {
                repliesCount: this.initialRepliesCount
            }
        }
    }
</script>