<template>
  <div>
    <el-row :gutter="20">
      <el-col :span="6" v-for="(item, index) in imageList" :key="index">
        <el-card :body-style="{ padding: '0px' }">
          <img :src="item.imageUrl" class="image"/>
          <div style="padding: 14px;">
            <div class="bottom clearfix">
              <time class="time">{{ item.date }}</time>
              <el-button type="text" class="button" @click="showCommentInput(index)">
                <i class="el-icon-chat-dot-round"></i> 评论 {{ item.comments }}
              </el-button>
              <el-button type="text" class="button" @click="like(index)">
                <i class="el-icon-thumb"></i> 点赞 {{ item.likes }}
              </el-button>
            </div>
            <div v-if="item.showCommentInput">
              <el-input
                  type="textarea"
                  :autosize="{ minRows: 2, maxRows: 4}"
                  placeholder="请输入评论"
                  v-model="item.commentInput"
              ></el-input>
              <el-button type="primary" size="small" @click="submitComment(index)">提交</el-button>
            </div>
          </div>
        </el-card>
      </el-col>
    </el-row>
  </div>
</template>
<script>
export default {
  data() {
    return {
      imageList: [
        {
          imageUrl: 'https://via.placeholder.com/150',
          date: '2021-08-01',
          comments: 10,
          likes: 20,
          showCommentInput: false,
          commentInput: '',
        },
        {
          imageUrl: 'https://via.placeholder.com/150',
          date: '2021-08-02',
          comments: 5,
          likes: 10,
          showCommentInput: false,
          commentInput: '',
        },
        // ... 更多图片数据
      ],
    };
  },
  methods: {
    showCommentInput(index) {
      this.$set(this.imageList[index], 'showCommentInput', true);
    },
    like(index) {
      this.$set(this.imageList[index], 'likes', this.imageList[index].likes + 1);
      // 提交点赞数据到服务器
      // ...
    },
    submitComment(index) {
      // 提交评论数据到服务器
      // ...
      this.$set(this.imageList[index], 'comments', this.imageList[index].comments + 1);
      this.$set(this.imageList[index], 'showCommentInput', false);
      this.$set(this.imageList[index], 'commentInput', '');
    },
  },
};
</script>
<style scoped>
.time {
  font-size: 13px;
  color: #999;
}

.bottom {
  margin-top: 13px;
  line-height: 12px;
}

.button {
  padding: 0;
  float: right;
}

.image {
  width: 100%;
  display: block;
}

.clearfix:before,
.clearfix:after {
  display: table;
  content: '';
}

.clearfix:after {
  clear: both;
}
</style>