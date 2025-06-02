import { BookType } from "./Book"

export type EditorType = {
    id: string,
    name: string
    books: BookType[];
}