import React, { useEffect, useState } from 'react';
import { useEditor, EditorContent } from '@tiptap/react';
import StarterKit from '@tiptap/starter-kit';
import Placeholder from '@tiptap/extension-placeholder';

function ToolbarButton({ active = false, label, onClick, disabled = false, children }) {
    return (
        <button
            type="button"
            onClick={onClick}
            disabled={disabled}
            title={label}
            aria-label={label}
            className={`inline-flex h-8 min-w-8 items-center justify-center rounded px-2 text-sm font-semibold transition disabled:cursor-not-allowed disabled:opacity-40 ${
                active
                    ? 'bg-white text-indigo-700 shadow-sm ring-1 ring-indigo-200'
                    : 'text-gray-600 hover:bg-white hover:text-gray-900'
            }`}
        >
            {children}
        </button>
    );
}

export default function RichTextEditor({
    value,
    onChange,
    placeholder = 'Start writing your essay here...',
    disabled = false,
    minHeight = 260,
}) {
    const [wordCount, setWordCount] = useState(0);

    const editor = useEditor({
        extensions: [
            StarterKit,
            Placeholder.configure({ placeholder }),
        ],
        content: value || '',
        editable: !disabled,
        editorProps: {
            attributes: {
                class: 'min-h-full focus:outline-none [&_p]:my-0 [&_p+p]:mt-3 [&_strong]:font-bold [&_em]:italic [&_ul]:my-3 [&_ul]:list-disc [&_ul]:pl-6 [&_ol]:my-3 [&_ol]:list-decimal [&_ol]:pl-6 [&_li]:my-1 [&_blockquote]:my-3 [&_blockquote]:border-l-4 [&_blockquote]:border-indigo-200 [&_blockquote]:pl-4 [&_blockquote]:text-gray-600',
            },
        },
        onCreate: ({ editor: createdEditor }) => {
            setWordCount(createdEditor.getText().trim().split(/\s+/).filter(Boolean).length);
        },
        onUpdate: ({ editor: updatedEditor }) => {
            const html = updatedEditor.getHTML();
            setWordCount(updatedEditor.getText().trim().split(/\s+/).filter(Boolean).length);
            if (html !== value) {
                onChange(html);
            }
        },
    });

    useEffect(() => {
        if (editor && value !== editor.getHTML()) {
            editor.commands.setContent(value || '', false);
            setWordCount(editor.getText().trim().split(/\s+/).filter(Boolean).length);
        }
    }, [value, editor]);

    useEffect(() => {
        editor?.setEditable(!disabled);
    }, [disabled, editor]);

    if (!editor) {
        return null;
    }

    return (
        <div className={`overflow-hidden rounded-lg border transition ${
            disabled
                ? 'border-gray-200 bg-gray-50'
                : 'border-gray-300 bg-white shadow-sm focus-within:border-indigo-500 focus-within:ring-2 focus-within:ring-indigo-100'
        }`}>
            {!disabled && (
                <div className="flex flex-wrap items-center gap-1 border-b border-gray-200 bg-gray-50 px-2 py-2">
                    <div className="flex items-center gap-1 border-r border-gray-200 pr-2">
                        <ToolbarButton label="Bold" active={editor.isActive('bold')} onClick={() => editor.chain().focus().toggleBold().run()}>
                            B
                        </ToolbarButton>
                        <ToolbarButton label="Italic" active={editor.isActive('italic')} onClick={() => editor.chain().focus().toggleItalic().run()}>
                            <span className="font-serif text-base italic">I</span>
                        </ToolbarButton>
                    </div>
                    <div className="flex items-center gap-1 border-r border-gray-200 pr-2">
                        <ToolbarButton label="Bulleted list" active={editor.isActive('bulletList')} onClick={() => editor.chain().focus().toggleBulletList().run()}>
                            List
                        </ToolbarButton>
                        <ToolbarButton label="Numbered list" active={editor.isActive('orderedList')} onClick={() => editor.chain().focus().toggleOrderedList().run()}>
                            1.
                        </ToolbarButton>
                        <ToolbarButton label="Quote" active={editor.isActive('blockquote')} onClick={() => editor.chain().focus().toggleBlockquote().run()}>
                            Quote
                        </ToolbarButton>
                    </div>
                    <div className="flex items-center gap-1">
                        <ToolbarButton label="Undo" disabled={!editor.can().undo()} onClick={() => editor.chain().focus().undo().run()}>
                            Undo
                        </ToolbarButton>
                        <ToolbarButton label="Redo" disabled={!editor.can().redo()} onClick={() => editor.chain().focus().redo().run()}>
                            Redo
                        </ToolbarButton>
                    </div>
                </div>
            )}

            <div className="p-4 text-sm leading-7 text-gray-800">
                <EditorContent editor={editor} style={{ minHeight }} />
            </div>

            <div className="flex items-center justify-between border-t border-gray-100 bg-white px-4 py-2 text-xs text-gray-500">
                <span>{disabled ? 'Complete Part A to unlock this response.' : 'Your response is saved automatically.'}</span>
                <span>{wordCount} {wordCount === 1 ? 'word' : 'words'}</span>
            </div>
        </div>
    );
}
