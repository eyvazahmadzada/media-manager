import axios from "./axios";

export default {
  // API endpoints
  getItems: () => axios.get("/get-items.php"),
  createItem: (itemData) => axios.post("/create-item.php", itemData),
  updateItem: (itemData) => axios.put("/update-item.php", itemData),
  deleteItem: (id) => axios.delete("/delete-item.php", { data: { id } })
};
