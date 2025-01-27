import { Box, Button, TextField, Alert } from "@mui/material";


import { useRef } from "react";
import { useParams, useNavigate } from "react-router-dom";
import { useQuery, useMutation } from "react-query";
import { queryClient } from "../ThemedApp";
import { useApp } from "../ThemedApp";
import {
    fetchPostComments,
    postComment,
    deleteComment,
} from "../libs/fetcher";
import Comment from "./Comment";

export default function Comments() {
    const { id } = useParams();
    const navigate = useNavigate();

    const contentInput = useRef();

    const { setGlobalMsg, auth } = useApp();

    const { isLoading, isError, error, data } = useQuery("comments", () => fetchPostComments(id));

    const addComment = useMutation(content => postComment(content, id), {
        onSuccess: async comment => {
            await queryClient.cancelQueries("comments");
            await queryClient.setQueryData("comments", old => [comment, ...old]);
            setGlobalMsg("A comment added");
        },
    });

    const removeComment = useMutation(async commentId => deleteComment(id, commentId), {
        onSuccess: async () => {
            queryClient.refetchQueries("comments");
            setGlobalMsg("A comment deleted");
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
            {auth && (
                <form
                    onSubmit={e => {
                        e.preventDefault();
                        const content = contentInput.current.value;
                        if (!content) return false;

                        addComment.mutate(content);

                        e.currentTarget.reset();
                    }}>
                    <Box
                        sx={{
                            display: "flex",
                            flexDirection: "column",
                            gap: 1,
                            mt: 3,
                        }}>
                        <TextField
                            inputRef={contentInput}
                            multiline
                            placeholder="Your Comment"
                        />
                        <Button
                            type="submit"
                            variant="contained">
                            Reply
                        </Button>
                    </Box>
                </form>
            )}

            {data.map(comment => {
                return (
                    <Comment
                        key={comment.id}
                        comment={comment}
                        remove={() => removeComment.mutate(comment.id)}
                    />
                );
            })}


        </Box>
    );
}