import { useEffect, useState } from "react";
import Item from "../components/Post";
import Form from "../components/Form";
import { queryClient, useApp } from "../ThemedApp";
import { Alert, Box } from "@mui/material";
import { useMutation, useQuery } from "react-query";
import { deletePost, fetchPosts, fetchReactionTypes, postPost } from "../libs/fetcher";
import SearchForm from "../components/SearchForm";
import Post from "../components/Post";


export default function Home() {
  const { showForm, showSearchForm, setGlobalMsg, setReactionTypes, defaultReactionType, setDefaultReactionType } = useApp();
  const { isLoading, isError, error, data } = useQuery("posts", () => fetchPosts({ search: "" }));
  const { data: reactionTypesResult } = useQuery("reactionTypes", () => fetchReactionTypes({ search: "" }));


  useEffect(() => {
    if (reactionTypesResult?.data?.reactionTypes && reactionTypesResult?.data?.reactionTypes.length > 0) {
      const reactionTypes = reactionTypesResult?.data?.reactionTypes;
      setReactionTypes(reactionTypes);

      const reactionTypesCount = reactionTypes.length;

      for (let i = 0; i < reactionTypesCount; i++) {
        const element = reactionTypes[i];

        if (element.is_default) {
          setDefaultReactionType(reactionTypes[i]);
          break;
        }
      }

      // if (defaultReactionType == null) {
      //   console.log("no")
      //   setDefaultReactionType(reactionTypes[0]);
      // }
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