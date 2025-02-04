import { useEffect, useState } from "react";
import Item from "../components/Post";
import Form from "../components/Form";
import { queryClient, useApp } from "../ThemedApp";
import { Alert, Box, Button, Typography } from "@mui/material";
import { useMutation, useQuery } from "react-query";
import { deletePost, fetchPosts, fetchReactionTypes, postPost } from "../libs/fetcher";
import SearchForm from "../components/SearchForm";
import Post from "../components/Post";


export default function Home() {
  const [showLatest, setShowLatest] = useState(true);

  const { showForm, showSearchForm, setGlobalMsg, setReactionTypes, defaultReactionType, setDefaultReactionType } = useApp();
  const { isLoading, isError, error, data } = useQuery(["posts", showLatest], () => fetchPosts({ search: "", only_following_posts: !showLatest }));
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
      {showForm && <Form add={add.mutate} />}
      {showSearchForm && <SearchForm add={add.mutate} />}

      <Box
        sx={{
          display: "flex",
          justifyContent: "center",
          alignItems: "center",
          mb: 1,
        }}>
        <Button
          disabled={showLatest}
          onClick={() => setShowLatest(true)}>
          All
        </Button>
        <Typography sx={{ color: "text.fade", fontSize: 15 }}>
          |
        </Typography>
        <Button
          disabled={!showLatest}
          onClick={() => setShowLatest(false)}>
          Following
        </Button>
      </Box>

      {
        data.length == 0 && <p>No Post</p>
      }

      {data.map(post => {
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