import { BookType } from "./Book"
import { CategoryType } from "./Category"


export type ExcerptType = {
    id: string,
    text: string,
    bookStartedOn: string,
    bookEndedOn:string,
    bookPage: string,
    books: BookType,
    categories : CategoryType[],
}