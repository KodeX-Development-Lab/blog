import { useState } from "react";
import Item from "../components/Item";
import Form from "../components/Form";
import { queryClient, useApp } from "../ThemedApp";
import { Alert, Box } from "@mui/material";
import { ItemType } from "../types/types";
import { useMutation, useQuery } from "react-query";

const api = import.meta.env.VITE_API;

export default function Home() {
  const { showForm, setGlobalMsg } = useApp();
  const { isLoading, isError, error, data } = useQuery("posts", async () => {
    const res = await fetch(`${api}/content/posts`);
    return res.json();
  });

  const remove = useMutation(
    async id => {
      await fetch(`${api}/content/posts/${id}`, {
        method: "DELETE",
      });
    },
    {
      onMutate: id => {
        // queryClient.cancelQueries("posts");
        // queryClient.setQueryData("posts", (old) =>
        // 	old.filter(item => item.id !== id)
        // );
        setGlobalMsg("A post deleted");
      },
    }
  );

  const add = () => {
    setGlobalMsg("A post added");
  };

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
      {showForm && <Form add={add} />}

      {data.map(item => {
        return (
          <Item
            key={item.id}
            item={item}
            remove={remove.mutate}
          />
        );
      })}
    </Box>
  );
}