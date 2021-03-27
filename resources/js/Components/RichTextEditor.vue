<template>
  <div>
    <editor-menu-bar
      v-if="editable"
      :editor="editor"
      v-slot="{ commands, isActive }"
    >
      <div>
        <button
          :class="{ 'edit-clickable': isActive.bold() }"
          @click="commands.bold"
        >
          <em class="fas fa-bold"></em>
        </button>

        <button
          :class="{ 'edit-clickable': isActive.italic() }"
          @click="commands.italic"
        >
          <em class="fas fa-italic"></em>
        </button>

        <button
          :class="{ 'edit-clickable': isActive.strike() }"
          @click="commands.strike"
        >
          <em class="fas fa-strikethrough"></em>
        </button>

        <button
          :class="{ 'edit-clickable': isActive.underline() }"
          @click="commands.underline"
        >
          <em class="fas fa-underline"></em>
        </button>

        <button
          :class="{ 'edit-clickable': isActive.code() }"
          @click="commands.code"
        >
          <em class="fas fa-code"></em>
        </button>

        <button
          :class="{ 'edit-clickable': isActive.heading({ level: 1 }) }"
          @click="commands.heading({ level: 1 })"
        >
          H1
        </button>

        <button
          :class="{ 'edit-clickable': isActive.heading({ level: 2 }) }"
          @click="commands.heading({ level: 2 })"
        >
          H2
        </button>

        <button
          :class="{ 'edit-clickable': isActive.heading({ level: 3 }) }"
          @click="commands.heading({ level: 3 })"
        >
          H3
        </button>

        <button @click="commands.undo">
          <em class="fas fa-undo"></em>
        </button>

        <button @click="commands.redo">
          <em class="fas fa-redo"></em>
        </button>

        <button @click="saveText()">
          <em class="fas fa-paper-plane"></em>
        </button>
      </div>
    </editor-menu-bar>

    <editor-content class="editor__content" :editor="editor" />
  </div>
</template>

<script>
import { Editor, EditorContent, EditorMenuBar } from "tiptap";
import {
  Blockquote,
  CodeBlock,
  HardBreak,
  Heading,
  OrderedList,
  BulletList,
  ListItem,
  TodoItem,
  TodoList,
  Bold,
  Code,
  Italic,
  Link,
  Strike,
  Underline,
  History
} from "tiptap-extensions";

export default {
  components: {
    EditorContent,
    EditorMenuBar
  },
  props: {
    editable: {
      type: Boolean,
      required: true
    },
    incomingText: {
      type: String,
      required: true
    },
    onSave: {
      type: Function,
      required: false
    }
  },
  data() {
    return {
      editor: null,
      currentText: null
    };
  },
  mounted() {
    this.mountEditor();
  },
  beforeDestroy() {
    this.editor.destroy();
  },
  methods: {
    saveText() {
      this.editor.setContent(this.currentText);
      this.onSave(this.currentText);
    },
    mountEditor() {
      this.editor = new Editor({
        editable: this.editable,
        content: this.incomingText,
        extensions: [
          new Blockquote(),
          new CodeBlock(),
          new HardBreak(),
          new Heading({ levels: [1, 2, 3] }),
          new BulletList(),
          new OrderedList(),
          new ListItem(),
          new TodoItem(),
          new TodoList(),
          new Bold(),
          new Code(),
          new Italic(),
          new Link(),
          new Strike(),
          new Underline(),
          new History()
        ],
        onUpdate: ({ getHTML }) => {
          this.currentText = getHTML();
        }
      });
    }
  },
  watch: {
    editable() {
      this.editor.destroy();
      this.mountEditor();
    },
    incomingText() {
      this.editor.destroy();
      this.mountEditor();
    }
  }
};
</script>
