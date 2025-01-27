interface Post {
    id: string,
    name: string,
    content: string,
    myReaction?: object | null,
    reactionBrief: [],
    reactionsCount: number,
    commentsCount: number,
    createdAt: string,
    canEdit: boolean,
    canDelete: boolean,
    user: {
        id: number,
        name: string
    }
}

interface Comment {
    id: string,
    name: string,
    content: string,
    myReaction?: object | null,
    reactionBrief: [],
    reactionsCount: number,
    createdAt: string,
    canEdit: boolean,
    canDelete: boolean,
    user: {
        id: number,
        name: string
    }
}

export type {Post as PostType, Comment as CommentType}