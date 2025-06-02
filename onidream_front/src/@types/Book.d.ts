import { AuthorType } from "./Author"
import { EditorType } from "./Editor"
import { ExcerptType } from "./Excerpt"

export type BookType = {
    id: string,
    name: string,
    summary: string,
    isbn: string,
    publishedAt: string,
    editor: EditorType,
    authors: AuthorType[],
    excerpts?: ExcerptType[],
}