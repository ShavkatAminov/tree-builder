<template>
  <load :status="loading" @upload="uploadTree">
    <liquor-tree
      :data="tree"
      :options="options"
      ref="tree"
      @node:dragging:finish="logDragFinish"
    >
      <div slot-scope="{ node }" class="node-container">
        <div class="node-text">
          {{ node.text }}
        </div>
        <div class="node-control">
          <span class="badge badge-primary" @click.stop="editNode(node)">Edit</span>
          <span class="badge badge-success" @click.stop="addChildNode(node)">Add</span>
          <span class="badge badge-danger" @click.stop="removeNode(node)">Del</span>
        </div>
      </div>
    </liquor-tree>
  </load>
</template>

<script>
import Load from './loader/Load'
import LiquorTree from 'liquor-tree'

export default {
  name: 'Tree',
  data () {
    return {
      options: {
        checkbox: false,
        dnd: true
      },
      loading: 'load'
    }
  },
  components: {
    LiquorTree,
    Load
  },
  methods: {
    logDragFinish (targetNode, destinationNode, status) {
      var data = {
        targetNodeId: targetNode.id,
        destinationNodeId: destinationNode.id,
        status: status
      }
      this.$store.dispatch('updateNodePosition', data).then(resolve => {

      }).catch(reject => {
        console.log(reject)
      })
    },
    editNode (node, e) {
      node.startEditing()
      this.$refs.tree.$on('node:editing:stop', (node, prevNodeText) => {
        this.$store.dispatch('updateNodeText', { text: node.text, id: node.id }).then(resolve => {
        }).catch(reject => {
          node.text = prevNodeText
          console.log(reject)
        })
      })
    },
    removeNode (node) {
      if (confirm('Are you sure?')) {
        this.$store.dispatch('removeNode', { nodeId: node.id }).then(resolve => {
          node.remove()
        }).catch(reject => {
          console.log(reject)
        })
      }
    },

    addChildNode (node) {
      this.$store.dispatch('addChildNode', { parentId: node.id }).then(resolve => {
        node.append(resolve.data)
      }).catch(reject => {
        console.log(reject)
      })
    },
    uploadTree () {
      this.loading = 'load'
      this.$store.dispatch('fetchTree').then(() => {
        this.loading = 'success'
      }).catch(() => {
        this.loading = 'reload'
      })
    }
  },
  computed: {
    tree: {
      get () {
        return this.$store.state.tree
      },
      set (val) {
        //  this.$store.commit('UPDATE_TREE', val)
      }
    }
  },
  created () {
    this.uploadTree()
  }
}
</script>
