<template>
    <transition-group name="comments-effect" tag="ul" mode="out-in" class="dashboard-comments">
        <li class="comment-wrap" v-for="(comment, index) in comments" :key="comment.comment_ID">
            <div class="comment-details">
                <div class="details-top">
                    <img :src="comment.author_avatar" width="50" height="50"/>
                    <div class="details-top-right">
                        <h5>{{comment.comment_author}}</h5>
                        <span>at {{comment.comment_date_gmt}}</span>
                    </div>
                </div>
                <p class="comment-content">
                    {{comment.comment_content}}
                </p>
            </div>
            <div class="comment-actions">
                <button class="comment-approve comment-buttons" @click="changeCommentStatus(comment.comment_ID, 'approve', index)">Approve</button>
                <button class="comment-reject comment-buttons" @click="changeCommentStatus(comment.comment_ID, 'trash', index)">Reject</button>                
            </div>
        </li>
    </transition-group>
</template>

<script>
import Axios from 'axios'

export default {
    name: 'comment',
    props: {
        comments: {
            type: Array,            
        }
    },
    methods: {
        changeCommentStatus:function(commentId, commentStatus, index) {
            this.comments.splice(index, 1)
            Axios.post(nb.api_route + 'comments/', {
                id: commentId,
                status: commentStatus
            })
            .then(response => {
                console.log(response)
            })
            .catch(e => {
                console.log(e);
            })
        }
    }
}
</script>

<style lang="scss">
@import '../scss/variable.scss';

// .comments-effect-enter-active, .comments-effect-leave-active, .comment-wrap {
//     transition: all 5s;
// }

.comments-effect-enter-active, .comments-effect-leave-active {
    // transition: all 2s;
    opacity: 0;  
    position: absolute;
}

.comments-effect-move {
    transition: all .5s;
}

.comments-effect-enter, .comments-effect-leave-to {
    opacity: 0;  
    // transform: translateX(0);
}

.dashboard-comments {
    padding-left: 0;
}

.comment-wrap {
    display: inline-block;    
    width: 100%;    
    padding-bottom: 10px;
    &:not(:last-child) {
        margin-bottom: 15px;
        border-bottom: 1px solid #f3f3f3;
    }
    .comment-details {
        width: 70%;
        float: left;        
    }
    .details-top {
        display: flex;
        h5 {
            margin-top: 0;
            font-size: 14px;
            color: #333;
            margin-bottom: 0;
        }
        img {
            border-radius: 50%;
            margin-right: 15px;
        }
    }
    .details-top-right {
        span {
            color: #999
        }
    }
    .comment-content {
        font-size: 14px;
        color: #abc0d1;
        margin-top: 20px;
    }
    .comment-actions {
        width: 30%;
        float: left;  
        text-align: right;             
        .comment-approve {
            background-image: -webkit-linear-gradient(left, $db 0%, $lb 50%); /* For Safari 5.1 to 6.0 */
            background-image: -o-linear-gradient(right, $db 0%, $lb 50%); /* For Opera 11.1 to 12.0 */
            background-image: -moz-linear-gradient(right, $db 0%, $lb 50%); /* For Firefox 3.6 to 15 */
            background-image: linear-gradient(to right, $db 0%, $lb 50%);
            margin-right: 15px;
            &:hover {
                background-image: $db
            }
            @media(min-width: 1424px) and (max-width: 1903px) {
                margin-right: 0;
            }
            @media(max-width: 1023px) {
                margin-right: 0;
            }
        }
        .comment-reject {
            background-image: -webkit-linear-gradient(left, $dr 0%, $lr 50%); /* For Safari 5.1 to 6.0 */
            background-image: -o-linear-gradient(right, $dr 0%, $lr 50%); /* For Opera 11.1 to 12.0 */
            background-image: -moz-linear-gradient(right, $dr 0%, $lr 50%); /* For Firefox 3.6 to 15 */
            background-image: linear-gradient(to right, $dr 0%, $lr 50%);
            &:hover {
                background-image: $dr
            }
        }
        .comment-buttons {
            border-radius: 25px;
            padding: 10px 20px;
            color: #fff;
            transition: all .3s ease;
            background-size: 150% auto;
            box-shadow: 0px 4px 10px 0px #ccc;
            &:hover {
                background-position: 100% center;
                box-shadow: 0px 4px 8px 2px #ccc;
                transform: scale(1.02)
            }
        }
    }
}
</style>
