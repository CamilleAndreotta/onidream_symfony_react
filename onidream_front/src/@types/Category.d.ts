import { ExcerptType } from "./Excerpt"

export type CategoryType = {
    id: string,
    name: string,
    excerpts?: ExcerptType[],
}