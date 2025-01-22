import { useState } from "react";
import Item from "./components/Item";
import List from "./List";
import Form from "./components/Form";
import { useApp } from "./ThemedApp";
import { Box, Container } from "@mui/material";
import Header from "./components/Header";
import { ItemType } from "./types/types";


export default function App() {
  const { showForm } = useApp();

  const [data, setData] = useState<any>([
    { id: 1, content: "Hello, World!", name: "Alice" },
    { id: 2, content: "React is fun.", name: "Bob" },
    { id: 3, content: "Yay, interesting.", name: "Chris" },
  ]);

  const add = (content: string, name: string) => {
    const id = data[data.length - 1].id + 1;
    setData([...data, { id, content, name }]);
  };

  const remove = (id: number) => {
    setData(data.filter((item: any) => item.id !== id));
  };

  return (
    <Box>
      <Header />
      <Container
        maxWidth="sm"
        sx={{ mt: 4 }}>
        {showForm && <Form add={add} />}
        {data.map((item : ItemType) => {
          return (
            <Item
              key={item.id}
              item={item}
              remove={remove}
            />
          );
        })}
      </Container>
    </Box>
  );
}