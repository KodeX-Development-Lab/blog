import { Alert, Box } from "@mui/material";
import Post from "./Post";
import { useEffect } from "react";
import { useMutation, useQuery } from "react-query";
import { deletePost, fetchReactionTypes, fetchUserPosts } from "../libs/fetcher";
import { queryClient, useApp } from "../ThemedApp";

export default function UserPosts({ userId }: { userId: string | number }) {
    const { setGlobalMsg, setReactionTypes } = useApp();
    const { isLoading, isError, error, data } = useQuery("userPosts", () => fetchUserPosts({ userId, search: "" }));
    const { data: reactionTypesResult } = useQuery("reactionTypes", () => fetchReactionTypes({ search: "" }));


    useEffect(() => {
        if (reactionTypesResult?.data?.reactionTypes && reactionTypesResult?.data?.reactionTypes.length > 0) {
            const reactionTypes = reactionTypesResult?.data?.reactionTypes;
            setReactionTypes(reactionTypes);
        }
    }, [reactionTypesResult]);

    const remove = useMutation(async id => deletePost(id), {
        onMutate: async id => {
            await queryClient.cancelQueries("posts");
            await queryClient.setQueryData("posts", old =>
                old.filter(item => item.id !== id)
            );

            setGlobalMsg("A post deleted");
        },
    });

    const add = useMutation(content => postPost(content), {
        onSuccess: async post => {
            await queryClient.cancelQueries("posts");
            await queryClient.setQueryData("posts", old => [post, ...old]);
            setGlobalMsg("A post added");
        },
    });

    if (isError) {
        return (
            <Box>
                <Alert severity="warning">{error.message}</Alert>
            </Box>
        );
    }

    if (isLoading) {
        return <Box sx={{ textAlign: "center" }}>Loading...</Box>;
    }

    return (
        <Box>

            {
                data.length == 0 && <p>No Post</p>
            }

            {data?.data.posts.map(post => {
                return (
                    <Post
                        key={post.id}
                        post={post}
                        remove={remove.mutate}
                    />
                );
            })}
        </Box>
    );
}