interface Item {
    id: number,
    name: string,
    content: string,
    createdAt: string,
    user: {
        id: number,
        name: string
    }
}

export type {Item as ItemType}