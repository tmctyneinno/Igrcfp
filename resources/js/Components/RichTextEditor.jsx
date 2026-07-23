import React, { useEffect } from 'react';
import { useEditor, EditorContent } from '@tiptap/react';
import StarterKit from '@tiptap/starter-kit';
import Placeholder from '@tiptap/extension-placeholder';

export default function RichTextEditor({ 
    value, 
    onChange, 
    placeholder = "Start writing your essay here...", 
    disabled = false 
}) {
    const editor = useEditor({
        extensions: [
            StarterKit,
            Placeholder.configure({
                placeholder: placeholder,
            }),
        ],
        content: value || '',
        editable: !disabled,
        onUpdate: ({ editor }) => {
            // Get HTML content
            const html = editor.getHTML();
            // Only trigger change if content is different to avoid loops
            if (html !== value) {
                onChange(html);
            }
        },
    });

    // Update editor content if value changes from outside (e.g., loading saved answer)
    useEffect(() => {
        if (editor && value !== editor.getHTML()) {
            // We use commands.setContent to avoid triggering onUpdate loop unnecessarily
            // but we need to be careful not to trigger infinite re-renders
            const currentHtml = editor.getHTML();
            if (currentHtml !== value) {
                 editor.commands.setContent(value || '', false);
            }
        }
    }, [value, editor]);

    if (!editor) {
        return null;
    }

    return (
        <div className={`border rounded-xl overflow-hidden ${disabled ? 'bg-gray-50 opacity-75' : 'bg-white border-gray-300 focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500'}`}>
            {/* Toolbar */}
            {!disabled && (
                <div className="flex items-center gap-1 p-2 bg-gray-50 border-b border-gray-200">
                    <button
                        type="button"
                        onClick={() => editor.chain().focus().toggleBold().run()}
                        className={`p-2 rounded hover:bg-gray-200 ${editor.isActive('bold') ? 'bg-gray-200 text-blue-600' : 'text-gray-600'}`}
                        title="Bold"
                    >
                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 12h8a4 4 0 100-8H6v8zm0 0h8a4 4 0 110 8H6v-8z" /></svg>
                    </button>
                    <button
                        type="button"
                        onClick={() => editor.chain().focus().toggleItalic().run()}
                        className={`p-2 rounded hover:bg-gray-200 ${editor.isActive('italic') ? 'bg-gray-200 text-blue-600' : 'text-gray-600'}`}
                        title="Italic"
                    >
                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                    </button>
                    <button
                        type="button"
                        onClick={() => editor.chain().focus().toggleBulletList().run()}
                        className={`p-2 rounded hover:bg-gray-200 ${editor.isActive('bulletList') ? 'bg-gray-200 text-blue-600' : 'text-gray-600'}`}
                        title="Bullet List"
                    >
                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                    <button
                        type="button"
                        onClick={() => editor.chain().focus().toggleOrderedList().run()}
                        className={`p-2 rounded hover:bg-gray-200 ${editor.isActive('orderedList') ? 'bg-gray-200 text-blue-600' : 'text-gray-600'}`}
                        title="Numbered List"
                    >
                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M7 7h12M7 12h12M7 17h12M3 7h.01M3 12h.01M3 17h.01" /></svg>
                    </button>
                </div>
            )}
            
            {/* Editor Content */}
            <div className="min-h-[200px] p-4 prose prose-sm max-w-none focus:outline-none">
                <EditorContent editor={editor} />
            </div>
        </div>
    );
}